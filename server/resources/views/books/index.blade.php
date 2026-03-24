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
        <div class="cf-book-grid">
            @forelse ($books as $book)
                @php
                    $instructorName = $book->instructor?->name ?? __('Instructor');
                    $instructorAvatar = $book->instructor?->profile_image_url ?? \App\Support\MediaAsset::avatarFallback($instructorName);
                    $instructorAvatarFallback = $book->instructor?->profile_image_fallback_url ?? \App\Support\MediaAsset::avatarFallback($instructorName);
                @endphp
                <article class="cf-course-card cf-book-card">
                    <a href="{{ route('books.show', $book) }}" class="cf-course-media">
                        <img src="{{ $book->thumbnail_url }}" alt="{{ $book->title }}" class="cf-book-cover" loading="lazy" onerror="this.onerror=null;this.src='{{ $book->thumbnail_fallback_url }}';">
                    </a>
                    <div class="cf-book-card-body">
                        <div class="flex flex-wrap gap-2">
                            <span class="cf-course-meta-pill">{{ $book->is_free ? __('Free') : number_format((float) $book->price, 2).' '.strtoupper($book->currency ?: 'USD') }}</span>
                            <span class="cf-course-meta-pill">{{ __('Downloadable') }}</span>
                        </div>
                        <h2 class="cf-course-title mt-5 line-clamp-2">
                            <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                        </h2>
                        <p class="cf-course-description mt-4 cf-book-summary">{{ str($book->description)->limit(170) }}</p>
                        <div class="cf-course-footer cf-book-footer">
                            <a href="{{ route('instructor.show') }}" class="cf-book-instructor" title="{{ $instructorName }}">
                                <img
                                    src="{{ $instructorAvatar }}"
                                    alt="{{ $instructorName }}"
                                    class="cf-book-instructor-avatar"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ $instructorAvatarFallback }}';"
                                >
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-[var(--color-text-primary)]">{{ $instructorName }}</p>
                                    <p class="truncate text-xs text-[var(--color-text-muted)]">{{ __('View instructor') }}</p>
                                </div>
                            </a>
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

        @if ($books->hasPages())
            <div class="cf-pagination-shell mt-8">
                <div class="text-sm text-[var(--color-text-muted)]">
                    {{ __('Showing :from-:to of :total books', ['from' => $books->firstItem(), 'to' => $books->lastItem(), 'total' => $books->total()]) }}
                </div>

                <nav aria-label="{{ __('Books pagination') }}" class="flex flex-wrap items-center gap-2">
                    @if ($books->onFirstPage())
                        <span class="cf-pagination-link is-disabled">{{ __('Previous') }}</span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}" class="cf-pagination-link">{{ __('Previous') }}</a>
                    @endif

                    @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                        @if ($page === $books->currentPage())
                            <span aria-current="page" class="cf-pagination-link is-active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="cf-pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}" class="cf-pagination-link">{{ __('Next') }}</a>
                    @else
                        <span class="cf-pagination-link is-disabled">{{ __('Next') }}</span>
                    @endif
                </nav>
            </div>
        @endif
    </section>
</x-public-layout>
