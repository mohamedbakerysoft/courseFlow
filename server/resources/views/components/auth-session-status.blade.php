@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'cf-status-message']) }}>
        {{ $status }}
    </div>
@endif
