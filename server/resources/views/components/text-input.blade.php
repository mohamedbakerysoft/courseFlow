@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md shadow-sm border-[var(--color-secondary)]/30 focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]']) }}>
