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
