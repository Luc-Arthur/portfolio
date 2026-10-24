import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

/**
 * Animations d'entrée (hero) et révélations au scroll.
 *
 * - [data-hero]           : apparaît au chargement, dans l'ordre du DOM.
 * - [data-reveal]         : apparaît quand il entre dans l'écran.
 * - [data-reveal-stagger] : avec data-reveal, ses enfants directs apparaissent l'un après l'autre.
 * - [data-parallax]       : léger décalage vertical au scroll.
 */
export function initMotion(reduceMotion) {
    const root = document.documentElement;

    if (reduceMotion) {
        root.classList.remove('js-motion');
        return;
    }

    try {
        gsap.registerPlugin(ScrollTrigger);

        const heroItems = gsap.utils.toArray('[data-hero]');

        if (heroItems.length) {
            gsap.fromTo(
                heroItems,
                { opacity: 0, y: 40, filter: 'blur(8px)' },
                {
                    opacity: 1,
                    y: 0,
                    filter: 'blur(0px)',
                    duration: 1.1,
                    ease: 'power3.out',
                    stagger: 0.12,
                    delay: 0.15,
                    clearProps: 'filter',
                },
            );

            // Filet de sécurité : si les animations ne tournent pas (navigateur sans rendu
            // d'images, robot d'indexation…), le contenu du hero s'affiche quand même.
            setTimeout(() => {
                heroItems.forEach((el) => {
                    if (parseFloat(getComputedStyle(el).opacity) < 0.05) {
                        gsap.set(el, { clearProps: 'all' });
                        el.style.opacity = '1';
                    }
                });
            }, 3000);
        }

        gsap.utils.toArray('[data-reveal]').forEach((el) => {
            const staggered = el.hasAttribute('data-reveal-stagger') && el.children.length;

            if (staggered) {
                gsap.set(el, { opacity: 1 });
            }

            gsap.fromTo(
                staggered ? el.children : el,
                { opacity: 0, y: 36 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.9,
                    ease: 'power3.out',
                    stagger: 0.1,
                    scrollTrigger: { trigger: el, start: 'top 88%', once: true },
                },
            );
        });

        gsap.utils.toArray('[data-parallax]').forEach((el) => {
            gsap.to(el, {
                yPercent: -8,
                ease: 'none',
                scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true },
            });
        });
    } catch (error) {
        // En cas de problème, on n'empêche jamais l'affichage du contenu.
        root.classList.remove('js-motion');
    }
}
