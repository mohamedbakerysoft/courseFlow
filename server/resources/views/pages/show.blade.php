<x-public-layout :title="$page->title" :metaDescription="str($page->content)->limit(160)">
    <section class="cf-shell cf-section pt-8 sm:pt-10">
        <div class="cf-section-shell max-w-4xl mx-auto">
            <div class="max-w-2xl space-y-4">
                <span class="cf-kicker">{{ __('Legal information') }}</span>
                <h1 class="cf-heading">
                    {{ $page->title }}
                </h1>
                <p class="cf-subheading">
                    {{ __('Review the current :title page exactly as it appears to visitors on the storefront.', ['title' => $page->title]) }}
                </p>
            </div>
            @php
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
                <div class="prose prose-sm sm:prose max-w-none text-[var(--color-text-muted)] mt-8">
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
                            <p>{!! nl2br(e(implode("\n", $rest))) !!}</p>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="prose prose-sm sm:prose max-w-none text-[var(--color-text-muted)] mt-8">
                    {!! nl2br(e($page->content)) !!}
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
