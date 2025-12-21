@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[var(--color-text-muted)]']) }}>
    {{ $value ?? $slot }}
</label>
