@php $ar = app()->getLocale() === 'ar'; @endphp
<div class="bg-white">
    <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center gap-3 rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4">
                <svg class="h-5 w-5 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2l7 4v4c0 5-3.5 9.5-7 10-3.5-.5-7-5-7-10V6l7-4zm0 6a3 3 0 1 0 .001 6.001A3 3 0 0 0 12 8z"/>
                </svg>
                <p class="text-sm text-[var(--color-text-primary)]">
                    {{ $ar ? 'مدفوعات آمنة عبر سترايب وباي بال' : 'Secure payments with Stripe & PayPal' }}
                </p>
            </div>
            <div class="flex items-center gap-3 rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4">
                <svg class="h-5 w-5 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M5 12l5 5L20 7l-1.5-1.5L10 13l-3.5-3.5L5 12z"/>
                </svg>
                <p class="text-sm text-[var(--color-text-primary)]">
                    {{ $ar ? 'وصول فوري بعد التسجيل' : 'Instant access after enrollment' }}
                </p>
            </div>
            <div class="flex items-center gap-3 rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4">
                <svg class="h-5 w-5 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm1 5h-2v6h6v-2h-4V7z"/>
                </svg>
                <p class="text-sm text-[var(--color-text-primary)]">
                    {{ $ar ? 'نص ودّي حول استرداد خلال 30 يومًا' : '30‑day refund friendly copy' }}
                </p>
            </div>
            <div class="flex items-center gap-3 rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4">
                <svg class="h-5 w-5 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 1L3 5v6c0 5.25 3.75 10 9 12 5.25-2 9-6.75 9-12V5l-9-4zm0 6a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
                </svg>
                <p class="text-sm text-[var(--color-text-primary)]">
                    {{ $ar ? 'خصوصية أولاً · ملتزم باللائحة العامة لحماية البيانات' : 'Privacy‑first · GDPR‑friendly' }}
                </p>
            </div>
        </div>
    </div>
    </div>
