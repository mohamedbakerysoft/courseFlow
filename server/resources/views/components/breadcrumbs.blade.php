@props(['items' => []])
<nav aria-label="Breadcrumb" class="mb-4 text-sm">
    @foreach ($items as $index => $item)
        @if (!empty($item['url']) && $index < count($items) - 1)
            <a href="{{ $item['url'] }}" class="underline text-gray-700">{{ $item['label'] }}</a>
            <span class="text-gray-500">/</span>
        @else
            <span class="text-gray-700">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
