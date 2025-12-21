@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-2 pt-1 border-b-2 border-[var(--color-primary)] text-sm font-medium leading-5 text-[var(--color-text-primary)] focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-2 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:border-[var(--color-secondary)]/40 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
