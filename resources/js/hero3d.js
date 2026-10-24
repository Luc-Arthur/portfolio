import {
    AdditiveBlending,
    BufferAttribute,
    BufferGeometry,
    Color,
    Group,
    PerspectiveCamera,
    Points,
    Scene,
    ShaderMaterial,
    Vector2,
    WebGLRenderer,
} from 'three';

const vertexShader = /* glsl */ `
    attribute float aRand;
    uniform float uTime;
    uniform float uSize;
    uniform float uPixelRatio;
    uniform vec2 uPointer;
    varying float vAlpha;
    varying float vMix;

    void main() {
        vec3 p = position;
        vec3 n = normalize(p);

        // Respiration organique de la surface
        float wave = sin(p.x * 2.4 + uTime * 0.55)
                   * sin(p.y * 2.1 + uTime * 0.45)
                   * sin(p.z * 2.7 + uTime * 0.35);
        p += n * wave * 0.22;

        // La surface se soulève légèrement du côté du pointeur
        float facing = max(dot(n, normalize(vec3(uPointer, 1.0))), 0.0);
        p += n * pow(facing, 6.0) * 0.18;

        vec4 world = modelMatrix * vec4(p, 1.0);
        vec4 mv = viewMatrix * world;
        gl_Position = projectionMatrix * mv;
        gl_PointSize = uSize * uPixelRatio * (0.45 + aRand) / -mv.z;

        // Les points de devant brillent, ceux de derrière s'effacent
        vAlpha = smoothstep(-2.6, 1.6, world.z) * (0.35 + aRand * 0.65);
        vMix = clamp(wave * 2.2 + 0.5 + facing * 0.4, 0.0, 1.0);
    }
`;

const fragmentShader = /* glsl */ `
    uniform vec3 uColorA;
    uniform vec3 uColorB;
    varying float vAlpha;
    varying float vMix;

    void main() {
        float d = length(gl_PointCoord - 0.5);
        float a = smoothstep(0.5, 0.0, d);
        gl_FragColor = vec4(mix(uColorA, uColorB, vMix), a * vAlpha);
    }
`;

/** Répartition régulière de points sur une sphère (spirale de Fibonacci). */
function fibonacciSphere(count, radius) {
    const positions = new Float32Array(count * 3);
    const rand = new Float32Array(count);
    const golden = Math.PI * (3 - Math.sqrt(5));

    for (let i = 0; i < count; i++) {
        const y = 1 - (i / (count - 1)) * 2;
        const r = Math.sqrt(1 - y * y);
        const theta = golden * i;
        const jitter = radius * (1 + (Math.random() - 0.5) * 0.04);

        positions[i * 3] = Math.cos(theta) * r * jitter;
        positions[i * 3 + 1] = y * jitter;
        positions[i * 3 + 2] = Math.sin(theta) * r * jitter;
        rand[i] = Math.random();
    }

    return { positions, rand };
}

function makePoints({ count, radius, size, colorA, colorB, pixelRatio }) {
    const { positions, rand } = fibonacciSphere(count, radius);
    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new BufferAttribute(positions, 3));
    geometry.setAttribute('aRand', new BufferAttribute(rand, 1));

    const material = new ShaderMaterial({
        vertexShader,
        fragmentShader,
        transparent: true,
        depthWrite: false,
        blending: AdditiveBlending,
        uniforms: {
            uTime: { value: 0 },
            uSize: { value: size },
            uPixelRatio: { value: pixelRatio },
            uPointer: { value: new Vector2() },
            uColorA: { value: new Color(colorA) },
            uColorB: { value: new Color(colorB) },
        },
    });

    return new Points(geometry, material);
}

export function initHero3d(canvas) {
    let renderer;

    try {
        renderer = new WebGLRenderer({ canvas, alpha: true, antialias: false, powerPreference: 'low-power' });
    } catch {
        canvas.remove();
        return;
    }

    const small = window.innerWidth < 768;
    const pixelRatio = Math.min(window.devicePixelRatio, 1.75);
    renderer.setPixelRatio(pixelRatio);

    const scene = new Scene();
    const camera = new PerspectiveCamera(40, 1, 0.1, 50);
    camera.position.z = 7;

    const group = new Group();
    const core = makePoints({
        count: small ? 1800 : 3600,
        radius: 2.1,
        size: 46,
        colorA: '#2a4a85',
        colorB: '#f2c46b',
        pixelRatio,
    });
    const halo = makePoints({
        count: small ? 400 : 900,
        radius: 3.1,
        size: 22,
        colorA: '#283149',
        colorB: '#e3a43a',
        pixelRatio,
    });
    group.add(core, halo);
    scene.add(group);

    const materials = [core.material, halo.material];
    const pointer = { x: 0, y: 0, tx: 0, ty: 0 };

    function resize() {
        const { clientWidth: w, clientHeight: h } = canvas.parentElement;
        const narrow = w < 768;

        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();

        // Sur grand écran la sphère se place derrière la photo, à droite
        group.position.set(narrow ? 0 : 1.9, narrow ? 1.4 : 0, 0);
        group.scale.setScalar(narrow ? 0.8 : 1);
    }

    function onPointerMove(event) {
        pointer.tx = (event.clientX / window.innerWidth) * 2 - 1;
        pointer.ty = -(event.clientY / window.innerHeight) * 2 + 1;
    }

    let visible = true;
    const start = performance.now();

    function render() {
        requestAnimationFrame(render);
        if (!visible || document.hidden) return;

        const t = (performance.now() - start) / 1000;
        pointer.x += (pointer.tx - pointer.x) * 0.05;
        pointer.y += (pointer.ty - pointer.y) * 0.05;

        materials.forEach((m) => {
            m.uniforms.uTime.value = t;
            m.uniforms.uPointer.value.set(pointer.x, pointer.y);
        });

        group.rotation.y = t * 0.08 + pointer.x * 0.35;
        group.rotation.x = -pointer.y * 0.25;
        halo.rotation.z = -t * 0.03;

        renderer.render(scene, camera);
    }

    // Pause quand le hero n'est plus à l'écran (économise batterie et CPU)
    new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting;
    }).observe(canvas);

    resize();
    window.addEventListener('resize', resize);
    window.addEventListener('pointermove', onPointerMove, { passive: true });
    render();

    canvas.classList.remove('opacity-0');
}
