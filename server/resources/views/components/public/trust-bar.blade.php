@props(['copy' => []])

<section aria-label="{{ __('Platform proof') }}">
    <div class="cf-section-shell !px-5 !py-5 sm:!px-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="cf-trust-card">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="M3 9h18" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['trust_1_title'] ?? __('Fast, familiar checkout') }}</p>
                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $copy['trust_1_body'] ?? __('Card, PayPal, and manual payment options are presented in one clean flow.') }}</p>
                </div>
            </div>
            <div class="cf-trust-card">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M5 12l4 4 10-10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['trust_2_title'] ?? __('Instant enrollment access') }}</p>
                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $copy['trust_2_body'] ?? __('The moment enrollment is complete, the next lesson is ready to open.') }}</p>
                </div>
            </div>
            <div class="cf-trust-card">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M12 6v6l4 2" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['trust_3_title'] ?? __('Structured student journey') }}</p>
                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $copy['trust_3_body'] ?? __('Lessons stay ordered, progress stays visible, and the platform stays easy to follow.') }}</p>
                </div>
            </div>
            <div class="cf-trust-card">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M8 11V9a4 4 0 118 0v2" stroke-linecap="round" />
                        <rect x="6" y="11" width="12" height="9" rx="2" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['trust_4_title'] ?? __('Trust before purchase') }}</p>
                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $copy['trust_4_body'] ?? __('A visible instructor, clear pricing, and protected access make the offer feel credible.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
