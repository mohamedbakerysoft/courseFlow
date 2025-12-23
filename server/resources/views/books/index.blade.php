<x-public-layout :title="__('Books')" :metaDescription="__('Download free resources or unlock paid books from the instructor storefront.')">
    <section class="cf-shell cf-section pt-10 sm:pt-14">
        <x-breadcrumbs :items="[
            ['label' => __('Home'), 'url' => url('/')],
            ['label' => __('Books')],
        ]" />

        <div class="mt-6 space-y-4">
            <span class="cf-kicker">{{ __('Downloadable library') }}</span>
            <h1 class="cf-display text-4xl sm:text-5xl">{{ __('Books and practical resources') }}</h1>
            <p class="cf-subheading max-w-3xl">{{ __('Offer lead magnets, premium ebooks, and downloadable guides beside your courses with the same clean storefront experience.') }}</p>
        </div>
    </section>

    <section class="cf-shell pb-14 sm:pb-16 lg:pb-20">
        <div class="cf-card-grid">
            @forelse ($books as $book)
                <article class="cf-course-card">
                    <a href="{{ route('books.show', $book) }}" class="cf-course-media">
                        <img src="{{ $book->thumbnail_url }}" alt="{{ $book->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $book->thumbnail_fallback_url }}';">
                    </a>
                    <div class="cf-course-body">
                        <div class="flex flex-wrap gap-2">
                            <span class="cf-course-meta-pill">{{ $book->is_free ? __('Free') : number_format((float) $book->price, 2).' '.strtoupper($book->currency ?: 'USD') }}</span>
                            <span class="cf-course-meta-pill">{{ __('Downloadable') }}</span>
                        </div>
                        <h2 class="cf-course-title mt-5">
                            <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                        </h2>
                        <p class="cf-course-summary mt-4">{{ str($book->description)->limit(150) }}</p>
                        <div class="cf-course-footer">
                            <span class="cf-course-summary">{{ $book->instructor?->name ?? __('Instructor') }}</span>
                            <a href="{{ route('books.show', $book) }}" class="cf-course-cta">{{ __('View book') }}</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="cf-panel px-8 py-10 text-center text-[var(--color-text-muted)]">
                    {{ __('No books available yet.') }}
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>
    </section>
</x-public-layout>
