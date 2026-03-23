import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const THEME_KEY = 'courseflow-theme';

function _currentTheme() {
    return document.documentElement.classList.contains('theme-dark') ? 'dark' : 'light';
}

function _syncThemeControls(theme) {
    const label = theme === 'dark' ? 'Light mode' : 'Dark mode';

    document.querySelectorAll('[data-theme-icon="light"]').forEach((icon) => {
        icon.classList.toggle('hidden', theme === 'dark');
    });

    document.querySelectorAll('[data-theme-icon="dark"]').forEach((icon) => {
        icon.classList.toggle('hidden', theme !== 'dark');
    });

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.setAttribute('aria-label', label);
        button.setAttribute('title', label);
    });
}

function _applyTheme(theme) {
    const resolvedTheme = theme === 'dark' ? 'dark' : 'light';
    document.documentElement.classList.toggle('theme-dark', resolvedTheme === 'dark');
    document.documentElement.dataset.theme = resolvedTheme;

    try {
        window.localStorage.setItem(THEME_KEY, resolvedTheme);
    } catch (error) {
        // noop
    }

    _syncThemeControls(resolvedTheme);
}

function _initThemeToggle() {
    _syncThemeControls(_currentTheme());

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            _applyTheme(_currentTheme() === 'dark' ? 'light' : 'dark');
        });
    });
}

document.addEventListener('DOMContentLoaded', _initThemeToggle);

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

function _isRightClickEnabled() {
    const body = document.body;
    if (!body) return true;
    const v = body.getAttribute('data-right-click-enabled');
    return v !== '0';
}
document.addEventListener('contextmenu', function (e) {
    if (_isRightClickEnabled()) return;
    e.preventDefault();
}, { passive: false });

document.addEventListener('selectstart', function (e) {
    const t = e.target;
    const isFormControl = t instanceof Element && t.closest('input, textarea, select');
    const allow = (t && t.isContentEditable === true) || isFormControl;
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

function _syncRichEditor(editor) {
    const surface = editor.querySelector('[data-editor-surface]');
    const input = editor.querySelector('[data-editor-input]');

    if (!surface || !input) return;
    input.value = surface.innerHTML.trim();
}

function _initRichEditors() {
    document.querySelectorAll('[data-rich-editor]').forEach((editor) => {
        const surface = editor.querySelector('[data-editor-surface]');
        const input = editor.querySelector('[data-editor-input]');
        const imageInput = editor.querySelector('[data-editor-image-input]');
        const uploadUrl = editor.getAttribute('data-image-upload-url') || '/dashboard/rich-text/images';

        if (!surface || !input || editor.dataset.richEditorReady === '1') return;
        editor.dataset.richEditorReady = '1';

        editor.querySelectorAll('[data-editor-command]').forEach((button) => {
            button.addEventListener('click', () => {
                const command = button.getAttribute('data-editor-command');
                if (!command) return;
                surface.focus();
                document.execCommand(command, false);
                _syncRichEditor(editor);
            });
        });

        editor.querySelectorAll('[data-editor-block]').forEach((button) => {
            button.addEventListener('click', () => {
                const block = button.getAttribute('data-editor-block');
                if (!block) return;
                surface.focus();
                document.execCommand('formatBlock', false, block);
                _syncRichEditor(editor);
            });
        });

        editor.querySelectorAll('[data-editor-link]').forEach((button) => {
            button.addEventListener('click', () => {
                const url = window.prompt('Enter URL');
                if (!url) return;
                surface.focus();
                document.execCommand('createLink', false, url);
                _syncRichEditor(editor);
            });
        });

        editor.querySelectorAll('[data-editor-image]').forEach((button) => {
            button.addEventListener('click', () => {
                imageInput?.click();
            });
        });

        imageInput?.addEventListener('change', async () => {
            const file = imageInput.files?.[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

            try {
                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Upload failed');
                }

                const payload = await response.json();
                surface.focus();
                document.execCommand('insertImage', false, payload.url);
                _syncRichEditor(editor);
            } catch (error) {
                console.error(error);
                window.alert('Image upload failed. Please try again.');
            } finally {
                imageInput.value = '';
            }
        });

        surface.addEventListener('input', () => _syncRichEditor(editor));
        surface.closest('form')?.addEventListener('submit', () => _syncRichEditor(editor));
        _syncRichEditor(editor);
    });
}

document.addEventListener('DOMContentLoaded', _initRichEditors);

function _initCourseOrganizers() {
    document.querySelectorAll('[data-course-organizer]').forEach((container) => {
        if (container.dataset.courseOrganizerReady === '1') return;
        container.dataset.courseOrganizerReady = '1';

        const moduleList = container.querySelector('[data-module-sorter-list]');
        const status = container.querySelector('[data-course-organizer-status]');
        const moduleReorderUrl = container.getAttribute('data-module-reorder-url');
        const lessonReorderUrl = container.getAttribute('data-lesson-reorder-url');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

        if (!moduleList || !moduleReorderUrl || !lessonReorderUrl) return;

        let draggedModule = null;
        let draggedLesson = null;
        const setStatus = (message, isError = false) => {
            if (!status) return;
            status.textContent = message;
            status.classList.toggle('text-[var(--color-error)]', isError);
            status.classList.toggle('text-[var(--color-text-muted)]', !isError);
        };

        const refreshModuleBadges = () => {
            Array.from(moduleList.querySelectorAll('[data-module-id]')).forEach((moduleCard, moduleIndex) => {
                const badge = moduleCard.querySelector('[data-module-order-badge]');
                if (badge) badge.textContent = `Module ${moduleIndex + 1}`;

                const lessonList = moduleCard.querySelector('[data-lesson-list]');
                if (!lessonList) return;

                Array.from(lessonList.querySelectorAll('[data-lesson-id]')).forEach((lessonCard, lessonIndex) => {
                    const lessonBadge = lessonCard.querySelector('[data-lesson-order-badge]');
                    if (lessonBadge) lessonBadge.textContent = `${lessonIndex + 1}`;
                });
            });
        };

        const saveModuleOrder = async () => {
            const moduleIds = Array.from(moduleList.querySelectorAll('[data-module-id]')).map((item) => Number(item.getAttribute('data-module-id')));

            setStatus('Saving module order...');

            try {
                const response = await fetch(moduleReorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ module_ids: moduleIds }),
                });

                if (!response.ok) throw new Error('Unable to save module order.');
                refreshModuleBadges();
                setStatus('Module order saved.');
            } catch (error) {
                console.error(error);
                setStatus('Could not save the new module order.', true);
            }
        };

        const saveLessonOrder = async () => {
            const modulesPayload = Array.from(moduleList.querySelectorAll('[data-module-id]')).map((moduleCard) => {
                const lessonList = moduleCard.querySelector('[data-lesson-list]');
                return {
                    module_id: Number(moduleCard.getAttribute('data-module-id')),
                    lesson_ids: lessonList
                        ? Array.from(lessonList.querySelectorAll('[data-lesson-id]')).map((lessonCard) => Number(lessonCard.getAttribute('data-lesson-id')))
                        : [],
                };
            });

            setStatus('Saving lesson order...');

            try {
                const response = await fetch(lessonReorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ modules: modulesPayload }),
                });

                if (!response.ok) throw new Error('Unable to save lesson order.');
                refreshModuleBadges();
                setStatus('Lesson order saved.');
            } catch (error) {
                console.error(error);
                setStatus('Could not save the lesson order.', true);
            }
        };

        const moduleCards = () => Array.from(moduleList.querySelectorAll('[data-module-id]'));
        const lessonLists = () => Array.from(moduleList.querySelectorAll('[data-lesson-list]'));

        moduleCards().forEach((moduleCard) => {
            const moduleHandle = moduleCard.querySelector('[data-module-drag-handle]');
            if (moduleHandle) {
                moduleHandle.addEventListener('dragstart', (event) => {
                    if (event.dataTransfer) {
                        event.dataTransfer.effectAllowed = 'move';
                        event.dataTransfer.setData('text/plain', moduleCard.getAttribute('data-module-id') || '');
                    }

                    draggedModule = moduleCard;
                    moduleCard.classList.add('opacity-60');
                    setStatus('Drop the module where you want it.');
                });

                moduleHandle.addEventListener('dragend', () => {
                    moduleCard.classList.remove('opacity-60');
                    draggedModule = null;
                });
            }

            moduleCard.addEventListener('dragover', (event) => {
                if (!draggedModule || draggedLesson) return;
                event.preventDefault();
            });

            moduleCard.addEventListener('drop', async (event) => {
                if (!draggedModule || draggedLesson || draggedModule === moduleCard) return;
                event.preventDefault();

                const items = moduleCards();
                const draggedIndex = items.indexOf(draggedModule);
                const targetIndex = items.indexOf(moduleCard);

                if (draggedIndex < targetIndex) {
                    moduleCard.after(draggedModule);
                } else {
                    moduleCard.before(draggedModule);
                }

                refreshModuleBadges();
                await saveModuleOrder();
            });

            moduleCard.querySelectorAll('[data-lesson-id]').forEach((lessonCard) => {
                const lessonHandle = lessonCard.querySelector('[data-lesson-drag-handle]');
                if (lessonHandle) {
                    lessonHandle.addEventListener('dragstart', (event) => {
                        event.stopPropagation();
                        if (event.dataTransfer) {
                            event.dataTransfer.effectAllowed = 'move';
                            event.dataTransfer.setData('text/plain', lessonCard.getAttribute('data-lesson-id') || '');
                        }

                        draggedLesson = lessonCard;
                        lessonCard.classList.add('opacity-60');
                        setStatus('Drop the lesson into the module and position you want.');
                    });

                    lessonHandle.addEventListener('dragend', (event) => {
                        event.stopPropagation();
                        lessonCard.classList.remove('opacity-60');
                        draggedLesson = null;
                    });
                }

                lessonCard.addEventListener('dragover', (event) => {
                    if (!draggedLesson) return;
                    event.stopPropagation();
                    event.preventDefault();
                });

                lessonCard.addEventListener('drop', async (event) => {
                    if (!draggedLesson || draggedLesson === lessonCard) return;
                    event.stopPropagation();
                    event.preventDefault();

                    const currentList = lessonCard.closest('[data-lesson-list]');
                    if (!currentList) return;

                    const items = Array.from(currentList.querySelectorAll('[data-lesson-id]'));
                    const draggedIndex = items.indexOf(draggedLesson);
                    const targetIndex = items.indexOf(lessonCard);

                    if (draggedIndex < targetIndex) {
                        lessonCard.after(draggedLesson);
                    } else {
                        lessonCard.before(draggedLesson);
                    }

                    refreshModuleBadges();
                    await saveLessonOrder();
                });
            });
        });

        lessonLists().forEach((lessonList) => {
            lessonList.addEventListener('dragover', (event) => {
                if (!draggedLesson) return;
                event.stopPropagation();
                event.preventDefault();
            });

            lessonList.addEventListener('drop', async (event) => {
                if (!draggedLesson) return;
                event.stopPropagation();
                event.preventDefault();

                if (event.target.closest('[data-lesson-id]')) return;

                lessonList.appendChild(draggedLesson);
                refreshModuleBadges();
                await saveLessonOrder();
            });
        });

        refreshModuleBadges();
    });
}

document.addEventListener('DOMContentLoaded', _initCourseOrganizers);
