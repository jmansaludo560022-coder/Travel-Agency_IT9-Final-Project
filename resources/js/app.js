import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ── Theme Toggle ──────────────────────────────────────────────
function applyTheme(theme) {
    const root = document.documentElement;
    if (theme === 'dark') {
        root.classList.add('dark');
    } else {
        root.classList.remove('dark');
    }
    // Update icons
    const sun  = document.getElementById('icon-sun');
    const moon = document.getElementById('icon-moon');
    if (sun && moon) {
        sun.classList.toggle('hidden', theme !== 'dark');
        moon.classList.toggle('hidden', theme === 'dark');
    }
}

window.toggleTheme = function () {
    const current = localStorage.getItem('theme') || 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    localStorage.setItem('theme', next);
    applyTheme(next);
};

// Apply saved theme immediately on load
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('theme') || 'light';
    applyTheme(saved);
});

// Prevent flash — also add inline script in <head> via layout
