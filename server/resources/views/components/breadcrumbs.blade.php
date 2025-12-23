@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-5">
    <div class="cf-floating-pill inline-flex flex-wrap items-center gap-2 px-3.5 py-2.5">
        @foreach ($items as $index => $item)
            @if (!empty($item['url']) && $index < count($items) - 1)
                <a href="{{ $item['url'] }}" class="text-[14px] font-medium !text-[var(--color-text-muted)] transition hover:!text-[var(--color-text-primary)]">
                    {{ $item['label'] }}
                </a>
                <span class="text-[14px] font-medium text-[color:rgba(74,74,74,0.7)] dark:text-[color:rgba(244,244,244,0.58)]">/</span>
            @else
                <span class="text-[14px] font-semibold !text-[var(--color-text-primary)]">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </div>
</nav>
