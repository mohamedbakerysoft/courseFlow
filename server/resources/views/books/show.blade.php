<x-public-layout :title="$book->title" :metaDescription="str($book->description)->limit(160)">
    @php
        $thumbnail = $book->thumbnail_url;
        $thumbnailFallback = $book->thumbnail_fallback_url;
        $hasDownload = filled($book->download_file_path);
    @endphp

    <section class="cf-shell cf-section pt-10 sm:pt-14">
        <x-breadcrumbs :items="[
            ['label' => __('Home'), 'url' => url('/')],
            ['label' => __('Books'), 'url' => route('books.index')],
            ['label' => $book->title],
        ]" />

        <div class="mt-6 grid gap-6 lg:grid-cols-[1.05fr,0.95fr] lg:items-center">
            <div class="space-y-5">
                <a href="{{ route('books.index') }}" class="cf-button-ghost !px-4 !py-2">{{ __('Back to books') }}</a>
                <div class="flex flex-wrap gap-2">
                    <span class="cf-chip">{{ $displayPrice }}</span>
                    <span class="cf-chip">{{ __('Downloadable resource') }}</span>
                    <span class="cf-chip">{{ strtoupper($book->language ?: 'EN') }}</span>
                </div>
                <div class="space-y-4">
                    <h1 class="cf-display text-4xl sm:text-5xl">{{ $book->title }}</h1>
                    @if (!empty($book->description))
                        <p class="cf-subheading max-w-3xl">{{ str($book->description)->limit(240) }}</p>
                    @endif
                </div>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="cf-panel-soft px-4 py-3">
                        <p class="font-semibold text-[var(--color-text-primary)]">{{ $instructorName ?: ($book->instructor?->name ?? __('Instructor')) }}</p>
                        <p class="mt-1 text-[var(--color-text-muted)]">{{ __('Author / Instructor') }}</p>
                    </div>
                    <div class="cf-panel-soft px-4 py-3">
                        <p class="font-semibold text-[var(--color-text-primary)]">{{ $hasDownload ? __('File ready') : __('Upload pending') }}</p>
                        <p class="mt-1 text-[var(--color-text-muted)]">{{ __('PDF, EPUB, or archive delivery after access is granted') }}</p>
                    </div>
                </div>
            </div>

            <div class="cf-panel overflow-hidden">
                <img
                    src="{{ $thumbnail }}"
                    alt="{{ $book->title }}"
                    class="aspect-[16/11] w-full object-cover"
                    loading="lazy"
                    onerror="this.onerror=null;this.src='{{ $thumbnailFallback }}';"
                >
            </div>
        </div>
    </section>

    <section class="cf-shell pb-14 sm:pb-16 lg:pb-20">
        <div class="grid gap-8 lg:grid-cols-[1.1fr,0.9fr]">
            <div class="space-y-6">
                <div class="cf-panel px-6 py-6 sm:px-8">
                    <span class="cf-kicker">{{ __('About this book') }}</span>
                    <div class="mt-5 text-sm leading-8 text-[var(--color-text-muted)]">
                        {!! nl2br(e($book->description ?: __('This downloadable book is designed as a focused resource you can keep, revisit, and apply outside the lesson flow.'))) !!}
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <article class="cf-panel px-5 py-5">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Format') }}</p>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Delivered as a downloadable file the student can keep offline.') }}</p>
                    </article>
                    <article class="cf-panel px-5 py-5">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Access') }}</p>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Free download or one-time payment with the same checkout system used for courses.') }}</p>
                    </article>
                    <article class="cf-panel px-5 py-5">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Best for') }}</p>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Creators who want to sell guides, ebooks, workbooks, or bonus resources from one storefront.') }}</p>
                    </article>
                </div>

                <div class="cf-panel px-6 py-6 sm:px-8">
                    <div class="flex items-center gap-4">
                        <img src="{{ $instructorImageUrl }}" alt="{{ $instructorName }}" class="h-16 w-16 rounded-[20px] object-cover">
                        <div>
                            <span class="cf-kicker">{{ __('Instructor credibility') }}</span>
                            <h2 class="mt-2 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $instructorName }}</h2>
                        </div>
                    </div>
                    @if (!empty($instructorBio))
                        <p class="mt-5 text-sm leading-8 text-[var(--color-text-muted)]">{{ $instructorBio }}</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="cf-panel sticky top-28 px-6 py-6 sm:px-8">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $book->title }}</p>
                                <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Choose a payment method or download immediately if the book is free.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $displayPrice }}</p>
                                <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ __('Digital file') }}</p>
                            </div>
                        </div>

                        <div class="grid gap-2">
                            @if (session('status'))
                                <div class="cf-status-message">{{ session('status') }}</div>
                            @endif

                            @if ($hasDownload && ($book->is_free || $isEnrolled))
                                <a href="{{ route('books.download', $book) }}" class="cf-button-primary w-full">{{ __('Download book') }}</a>
                            @else
                                @guest
                                    @if ($book->is_free)
                                        <a href="{{ route('books.download', $book) }}" class="cf-button-primary w-full">{{ __('Download free book') }}</a>
                                    @else
                                        <a href="{{ route('login') }}" class="cf-button-primary w-full">{{ __('Login to purchase') }}</a>
                                    @endif
                                @else
                                    @if ($book->is_free)
                                        <a href="{{ route('books.download', $book) }}" class="cf-button-primary w-full">{{ __('Download free book') }}</a>
                                    @elseif ($hasAnyPaymentMethod)
                                        @if ($isStripeEnabled)
                                            <form action="{{ route('payments.checkout', $book) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="cf-button-primary w-full">{{ __('Pay securely with Card') }}</button>
                                            </form>
                                        @endif
                                        @if ($isPayPalEnabled)
                                            <form action="{{ route('payments.paypal.checkout', $book) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="cf-button-secondary w-full">{{ __('Checkout with PayPal') }}</button>
                                            </form>
                                        @endif
                                        @if ($hasManualPayment)
                                            <form action="{{ route('payments.manual.start', $book) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="cf-button-secondary w-full">{{ __('Request manual payment') }}</button>
                                            </form>
                                        @endif
                                    @else
                                        <div class="rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.04)] px-4 py-3 text-center text-sm text-[var(--color-text-muted)]">
                                            {{ __('Online payments are temporarily unavailable. Please contact the instructor.') }}
                                        </div>
                                    @endif
                                @endguest
                            @endif
                        </div>

                        <div class="cf-divider pt-4 text-sm text-[var(--color-text-muted)]">
                            <p>{{ __('Sell this like any other product in Learnova: clear price, one-time payment, and downloadable delivery after access is granted.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
