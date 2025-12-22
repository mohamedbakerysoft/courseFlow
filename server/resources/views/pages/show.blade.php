<x-public-layout :title="$page->title" :metaDescription="str($page->content)->limit(160)">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 sm:p-8">
            <h1 class="text-2xl sm:text-3xl font-semibold text-[var(--color-text-primary)] mb-4">
                {{ $page->title }}
            </h1>
            @php
                $locale = app()->getLocale();
                $blocks = preg_split("/\n\s*\n/", (string) $page->content) ?: [];
                $hasNumberedSections = false;
                foreach ($blocks as $b) {
                    $lines = preg_split("/\n/", (string) $b) ?: [];
                    $first = trim($lines[0] ?? '');
                    if ($first !== '' && preg_match('/^\d+\.\s+.+$/u', $first)) {
                        $hasNumberedSections = true;
                        break;
                    }
                }
            @endphp
            @if ($hasNumberedSections)
                <div class="prose prose-sm sm:prose max-w-none text-[var(--color-text-muted)] {{ $locale === 'ar' ? 'text-right' : '' }}">
                    @foreach ($blocks as $block)
                        @php
                            $lines = preg_split("/\n/", (string) $block) ?: [];
                            $first = trim($lines[0] ?? '');
                            $rest = array_slice($lines, 1);
                            $title = $first;
                            if (preg_match('/^\d+\.\s+(.+)$/u', $first, $m)) {
                                $title = $m[1];
                            }
                        @endphp
                        <h2 class="text-lg font-semibold text-[var(--color-text-primary)] mt-6 mb-2">{{ $title }}</h2>
                        @if (!empty($rest))
                            <p class="{{ $locale === 'ar' ? 'text-right' : '' }}">{!! nl2br(e(implode("\n", $rest))) !!}</p>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="prose prose-sm sm:prose max-w-none text-[var(--color-text-muted)] {{ $locale === 'ar' ? 'text-right' : '' }}">
                    {!! nl2br(e($page->content)) !!}
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
