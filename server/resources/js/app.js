import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function _mix(a, b, t) {
    return Math.round(a + (b - a) * t);
}
function _rgbToHex(r, g, b) {
    const toHex = (n) => n.toString(16).padStart(2, '0');
    return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
}
function _computePalette(img) {
    const w = Math.max(1, Math.min(64, img.naturalWidth || 64));
    const h = Math.max(1, Math.min(64, img.naturalHeight || 64));
    const canvas = document.createElement('canvas');
    canvas.width = w;
    canvas.height = h;
    const ctx = canvas.getContext('2d');
    if (!ctx) return null;
    ctx.drawImage(img, 0, 0, w, h);
    const data = ctx.getImageData(0, 0, w, h).data;
    let r = 0, g = 0, b = 0, count = 0;
    for (let i = 0; i < data.length; i += 4) {
        const a = data[i + 3];
        if (a === 0) continue;
        r += data[i];
        g += data[i + 1];
        b += data[i + 2];
        count++;
    }
    if (count === 0) return null;
    r = Math.round(r / count);
    g = Math.round(g / count);
    b = Math.round(b / count);
    const primary = _rgbToHex(r, g, b);
    const secondary = _rgbToHex(_mix(r, 0, 0.4), _mix(g, 0, 0.4), _mix(b, 0, 0.4));
    const accent = _rgbToHex(_mix(r, 255, 0.4), _mix(g, 255, 0.4), _mix(b, 255, 0.4));
    const hover = _rgbToHex(_mix(r, 0, 0.2), _mix(g, 0, 0.2), _mix(b, 0, 0.2));
    return { primary, secondary, accent, hover };
}
function _applyPalette(p) {
    const root = document.documentElement;
    root.style.setProperty('--color-primary', p.primary);
    root.style.setProperty('--color-primary-hover', p.hover);
    root.style.setProperty('--color-secondary', p.secondary);
    root.style.setProperty('--color-accent', p.accent);
}
function _shouldAutoOverride() {
    const cs = getComputedStyle(document.documentElement);
    const primary = cs.getPropertyValue('--color-primary').trim();
    const secondary = cs.getPropertyValue('--color-secondary').trim();
    const accent = cs.getPropertyValue('--color-accent').trim();
    return primary === '#4F46E5' && secondary === '#334155' && accent === '#10B981';
}
document.addEventListener('DOMContentLoaded', function () {
    const img = document.querySelector('header#hero img');
    if (!img) return;
    const run = function () {
        if (!_shouldAutoOverride()) return;
        const p = _computePalette(img);
        if (p) _applyPalette(p);
    };
    if (img.complete) {
        run();
    } else {
        img.addEventListener('load', run, { once: true });
    }
});

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
