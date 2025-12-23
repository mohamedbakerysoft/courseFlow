@props(['active'])

@php
$classes = ($active ?? false)
            ? 'cf-mobile-link is-active'
            : 'cf-mobile-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
