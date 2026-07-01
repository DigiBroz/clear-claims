import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-arrow-motif]').forEach((svg) => {
        const path = svg.querySelector('[data-arrow-path]');
        const glow = svg.querySelector('[data-arrow-glow]');
        const fill = svg.querySelector('[data-arrow-fill]');
        const head = svg.querySelector('[data-arrow-head]');
        const dots = svg.querySelectorAll('[data-arrow-dot]');
        const grid = svg.querySelector('[data-arrow-grid]');
        const axis = svg.querySelector('[data-arrow-axis]');
        const length = path.getTotalLength();
        const axisLength = axis.getTotalLength();

        gsap.set([path, glow], { strokeDasharray: length, strokeDashoffset: length });
        gsap.set(axis, { strokeDasharray: axisLength, strokeDashoffset: axisLength });
        gsap.set(fill, { opacity: 0 });
        gsap.set(grid, { opacity: 0 });
        gsap.set(head, { opacity: 0, scale: 0.4, transformOrigin: '362px 30px' });
        gsap.set(dots, { opacity: 0, scale: 0.3, transformOrigin: 'center' });

        gsap.timeline({
            scrollTrigger: {
                trigger: svg,
                start: 'top 80%',
                once: true,
            },
            onComplete: () => svg.classList.add('is-revealed'),
        })
            .to(grid, { opacity: 1, duration: 0.5, ease: 'power1.out' }, 0)
            .to(axis, { strokeDashoffset: 0, duration: 0.5, ease: 'power1.out' }, 0)
            .to([path, glow], { strokeDashoffset: 0, duration: 1.4, ease: 'power2.out' }, 0.35)
            .to(fill, { opacity: 1, duration: 1.2, ease: 'power2.out' }, 0.35)
            .to(dots[0], { opacity: 1, scale: 1, duration: 0.3, ease: 'back.out(2)' }, 0.5)
            .to(dots[1], { opacity: 1, scale: 1, duration: 0.3, ease: 'back.out(2)' }, 1.05)
            .to(head, { opacity: 1, scale: 1, duration: 0.35, ease: 'back.out(2.5)' }, '-=0.15');
    });

    document.querySelectorAll('main section').forEach((section) => {
        const targets = Array.from(section.querySelectorAll('h1, h2, h3, p, .rounded-xl, form')).filter((el) => {
            const card = el.closest('.rounded-xl');
            return !card || card === el;
        });

        gsap.from(targets, {
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

    document.querySelectorAll('[data-blob-drift]').forEach((blob, index) => {
        gsap.to(blob, {
            x: index % 2 === 0 ? 18 : -14,
            y: index % 2 === 0 ? -14 : 16,
            scale: 1.06,
            duration: 6 + index,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true,
        });
    });

    document.querySelectorAll('[data-float-chip]').forEach((chip, index) => {
        gsap.to(chip, {
            y: -10,
            duration: 3.4 + index * 0.4,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true,
            delay: index * 0.3,
        });
    });
});
