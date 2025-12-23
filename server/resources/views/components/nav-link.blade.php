@props(['active'])

@php
$classes = ($active ?? false)
            ? 'cf-nav-link is-active'
            : 'cf-nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
