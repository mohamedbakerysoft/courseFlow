@php $ar = app()->getLocale() === 'ar'; @endphp
<section aria-label="{{ $ar ? 'شريط الثقة' : 'Trust Bar' }}" class="bg-white">
    <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center gap-3 rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4">
                <svg class="h-5 w-5 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2" />
                    <path d="M3 9h18" />
                </svg>
                <p class="text-sm text-[var(--color-text-primary)]">
                    {{ $ar ? 'مدفوعات آمنة عبر سترايب وباي بال' : 'Secure payments (Stripe & PayPal)' }}
                </p>
            </div>
            <div class="flex items-center gap-3 rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4">
                <svg class="h-5 w-5 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M5 12l4 4 10-10" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="text-sm text-[var(--color-text-primary)]">
                    {{ $ar ? 'وصول فوري بعد التسجيل' : 'Instant access after enrollment' }}
                </p>
            </div>
            <div class="flex items-center gap-3 rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4">
                <svg class="h-5 w-5 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="6" y="4" width="12" height="16" rx="2" />
                    <path d="M9 8h6M9 12h6" />
                </svg>
                <p class="text-sm text-[var(--color-text-primary)]">
                    {{ $ar ? 'دفع لمرة واحدة (بدون اشتراك)' : 'One‑time payment (no subscription)' }}
                </p>
            </div>
            <div class="flex items-center gap-3 rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4">
                <svg class="h-5 w-5 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M8 11V9a4 4 0 118 0v2" stroke-linecap="round" />
                    <rect x="6" y="11" width="12" height="9" rx="2" />
                </svg>
                <p class="text-sm text-[var(--color-text-primary)]">
                    {{ $ar ? 'الخصوصية أولاً · متوافق مع اللائحة العامة لحماية البيانات' : 'Privacy‑first · GDPR‑friendly' }}
                </p>
            </div>
        </div>
    </div>
</section>
