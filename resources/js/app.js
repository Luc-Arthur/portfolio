import Alpine from 'alpinejs';
import { initMotion } from './motion';

window.Alpine = Alpine;

Alpine.start();

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

initMotion(reduceMotion);

// La scène 3D n'est chargée que sur les pages qui ont un canvas (l'accueil).
const heroCanvas = document.getElementById('hero-canvas');

if (heroCanvas && !reduceMotion) {
    import('./hero3d')
        .then(({ initHero3d }) => initHero3d(heroCanvas))
        .catch(() => heroCanvas.remove());
}
