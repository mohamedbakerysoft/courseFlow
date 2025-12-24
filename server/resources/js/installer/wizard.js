const el = document.getElementById('installer');
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '';

function showStep(name) {
    el.querySelectorAll('[data-step]').forEach(s => {
        s.classList.add('hidden');
    });
    const target = el.querySelector('[data-step="'+name+'"]');
    if (target) target.classList.remove('hidden');
}

el.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-action="check"]');
    if (btn) {
        fetch(el.querySelector('form[data-form="database"]')?.getAttribute('action').replace('/database', '/check'), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        }).then(r => r.json()).then(data => {
            const res = el.querySelector('[data-result]');
            if (!res) return;
            if (data.ok) {
                res.innerHTML = '<div class="rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">Environment OK</div>';
                showStep('database');
            } else {
                const missing = (data.missing_extensions || []).join(', ');
                const notWritable = (data.not_writable || []).join(', ');
                res.innerHTML = '<div class="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">Errors: Missing '+missing+'; Not writable '+notWritable+'</div>';
            }
        });
    }
});

el.addEventListener('submit', function (e) {
    const form = e.target.closest('form[data-form]');
    if (!form) return;
    e.preventDefault();
    const fd = new FormData(form);
    const action = form.getAttribute('action');
    fetch(action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: fd
    }).then(r => r.json()).then(data => {
        if (form.dataset.form === 'database') {
            const res = el.querySelector('[data-db-result]');
            if (data.ok) {
                res.innerHTML = '<div class="rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">.env saved</div>';
                showStep('migrate');
            } else {
                res.innerHTML = '<div class="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">Failed</div>';
            }
        } else if (form.dataset.form === 'migrate') {
            const res = el.querySelector('[data-migrate-result]');
            if (data.ok) {
                res.innerHTML = '<div class="rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">Migrations complete</div>';
                showStep('admin');
            } else {
                res.innerHTML = '<div class="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">Failed</div>';
            }
        } else if (form.dataset.form === 'admin') {
            const res = el.querySelector('[data-admin-result]');
            if (data.ok) {
                res.innerHTML = '<div class="rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">Admin created</div>';
                showStep('finish');
            } else {
                res.innerHTML = '<div class="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">Failed</div>';
            }
        }
    });
});

showStep('check');
