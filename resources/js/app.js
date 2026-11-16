import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');

    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            const isHidden = menu.classList.toggle('hidden');
            toggle.setAttribute('aria-expanded', String(!isHidden));
        });
    }

    if (!('IntersectionObserver' in window)) {
        return;
    }

    const revealItems = document.querySelectorAll(
        'main > section:not(:first-child), .overview-card, .dashboard-overview-card, .card, .photo-card, .feature-visual',
    );
    revealItems.forEach((item, index) => {
        item.classList.add('motion-reveal');
        item.style.setProperty('--motion-delay', `${Math.min((index % 5) * 70, 280)}ms`);
    });
    document.body.classList.add('motion-ready');

    const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                currentObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealItems.forEach((item) => observer.observe(item));
});
