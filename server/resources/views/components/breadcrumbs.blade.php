@props(['items' => []])
<nav aria-label="Breadcrumb" class="mb-4 text-sm">
    @foreach ($items as $index => $item)
        @if (!empty($item['url']) && $index < count($items) - 1)
            <a href="{{ $item['url'] }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">{{ $item['label'] }}</a>
            <span class="text-[var(--color-text-muted)]">/</span>
        @else
            <span class="text-[var(--color-text-muted)]">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
