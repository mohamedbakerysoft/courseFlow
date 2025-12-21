import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
}, { passive: false });

document.addEventListener('selectstart', function (e) {
    const t = e.target;
    const allow = t && (t.closest('input, textarea, select') || t.isContentEditable === true);
    if (!allow) {
        e.preventDefault();
    }
}, { passive: false });

document.addEventListener('keydown', function (e) {
    const k = e.key.toLowerCase();
    if (k === 'f12') {
        e.preventDefault();
        return;
    }
    if ((e.ctrlKey || e.metaKey) && (k === 'i' || k === 'u' || k === 's')) {
        e.preventDefault();
        return;
    }
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && (k === 'i' || k === 'c' || k === 'j')) {
        e.preventDefault();
        return;
    }
}, { passive: false });
