<section aria-label="Demo notice">
    <div class="cf-panel-soft px-5 py-4 border-2 border-[var(--color-primary)]/20 bg-[var(--color-primary)]/5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-bold text-[var(--color-text-primary)]">
                    {{ __('Demo Environment Active') }}
                </p>
                <p class="mt-1 text-sm text-[var(--color-text-muted)]">
                    {{ __('Feel free to test the entire platform, including the checkout flow.') }}
                </p>
            </div>
            <div class="rounded-[14px] bg-white dark:bg-[#1a1a1a] shadow-sm border border-[rgba(15,23,42,0.06)] dark:border-white/[0.06] px-4 py-3 text-xs w-full lg:w-auto">
                <p class="font-bold text-[var(--color-primary)] mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('PayPal Sandbox (Buyer Test Account):') }}
                </p>
                <div class="grid grid-cols-[auto_1fr] gap-x-3 gap-y-1.5 text-[var(--color-text-muted)]">
                    <span class="font-medium">{{ __('Email:') }}</span>
                    <span class="font-mono select-all font-medium text-[var(--color-text-primary)]">sb-ftn2t50151885@personal.example.com</span>
                    <span class="font-medium">{{ __('Password:') }}</span>
                    <span class="font-mono select-all font-medium text-[var(--color-text-primary)]">2F/thMUa</span>
                </div>
            </div>
        </div>
    </div>
</section>
