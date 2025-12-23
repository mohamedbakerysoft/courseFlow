@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[15px] font-semibold tracking-[0.005em] text-[var(--color-text-primary)]']) }}>
    {{ $value ?? $slot }}
</label>
