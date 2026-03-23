<div class="flex flex-wrap items-center gap-3">
    <a
        href="{{ route('dashboard.finance.index') }}"
        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ ($financeSection ?? 'insights') === 'insights' ? 'bg-[var(--color-primary)] text-[var(--color-secondary)]' : 'border border-[rgba(15,23,42,0.08)] bg-white text-[var(--color-text-primary)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]' }}"
    >
        {{ __('Insights / Sales') }}
    </a>
    <a
        href="{{ route('dashboard.finance.manual_payments') }}"
        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition {{ ($financeSection ?? '') === 'manual-payments' ? 'bg-[var(--color-primary)] text-[var(--color-secondary)]' : 'border border-[rgba(15,23,42,0.08)] bg-white text-[var(--color-text-primary)] hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]' }}"
    >
        {{ __('Manual Payment Requests') }}
    </a>
</div>
