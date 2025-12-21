@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[var(--color-primary)] text-start text-base font-medium text-[var(--color-primary)] bg-[var(--color-primary)]/10 focus:outline-none focus:text-[var(--color-primary)] focus:bg-[var(--color-primary)]/15 focus:border-[var(--color-primary)] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:bg-[var(--color-secondary)]/10 hover:border-[var(--color-secondary)]/40 focus:outline-none focus:text-[var(--color-text-primary)] focus:bg-[var(--color-secondary)]/10 focus:border-[var(--color-secondary)]/40 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
