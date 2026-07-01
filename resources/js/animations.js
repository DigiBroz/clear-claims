import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-arrow-motif]').forEach((svg) => {
        const path = svg.querySelector('[data-arrow-path]');
        const head = svg.querySelector('[data-arrow-head]');
        const length = path.getTotalLength();

        gsap.set(path, { strokeDasharray: length, strokeDashoffset: length });
        gsap.set(head, { opacity: 0 });

        gsap.timeline({
            scrollTrigger: {
                trigger: svg,
                start: 'top 80%',
                once: true,
            },
        })
            .to(path, { strokeDashoffset: 0, duration: 1.4, ease: 'power2.out' })
            .to(head, { opacity: 1, duration: 0.3 }, '-=0.2');
    });

    document.querySelectorAll('main section').forEach((section) => {
        gsap.from(section.querySelectorAll('h1, h2, h3, p, .rounded-xl, form'), {
            scrollTrigger: {
                trigger: section,
                start: 'top 85%',
                once: true,
            },
            opacity: 0,
            y: 24,
            duration: 0.6,
            stagger: 0.08,
            ease: 'power2.out',
        });
    });
});
