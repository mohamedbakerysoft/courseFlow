<x-public-layout :title="'Courses'" :metaDescription="'Browse published courses'">
    <section class="cf-shell cf-section pt-10 sm:pt-14">
        <x-breadcrumbs :items="[
            ['label' => __('Home'), 'url' => url('/')],
            ['label' => __('Courses')],
        ]" />

        <div class="mt-6 grid gap-6 lg:grid-cols-[1.05fr,0.95fr] lg:items-end">
            <div class="space-y-4">
                <span class="cf-kicker">{{ __('Course catalog') }}</span>
                <h1 class="cf-display text-4xl sm:text-5xl">
                    {{ __('Choose the course that best fits your current stage') }}
                </h1>
                <p class="cf-subheading max-w-2xl">
                    {{ __('Compare flagship programs, practical quickstarts, and focused specialty courses inside one premium catalog.') }}
                </p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('courses.index') }}" class="{{ $priceFilter === null ? 'cf-button-secondary' : 'cf-chip' }}">
                        {{ __('All courses: :count', ['count' => $catalogSummary['total']]) }}
                    </a>
                    <a href="{{ route('courses.index', ['pricing' => 'free']) }}" class="{{ $priceFilter === 'free' ? 'cf-button-secondary' : 'cf-chip' }}">
                        {{ __('Free courses: :count', ['count' => $catalogSummary['free']]) }}
                    </a>
                    <a href="{{ route('courses.index', ['pricing' => 'paid']) }}" class="{{ $priceFilter === 'paid' ? 'cf-button-secondary' : 'cf-chip' }}">
                        {{ __('Paid courses: :count', ['count' => $catalogSummary['paid']]) }}
                    </a>
                    <span class="cf-chip">{{ __('Instant enrollment') }}</span>
                </div>
            </div>
            <div class="cf-panel px-6 py-6 sm:px-7">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Best for quick start') }}</p>
                        <p class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Free courses') }}</p>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Use the free filter when you want a lower-risk entry point, sample teaching style, or practical first step before paying.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Best for deeper outcome') }}</p>
                        <p class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Paid courses') }}</p>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Choose paid programs when you want a complete roadmap, stronger depth, structured lessons, and a clearer business or skill outcome.') }}</p>
                    </div>
                </div>

                <div class="mt-5 rounded-[5px] border border-gray-200 bg-gray-50 px-5 py-4 dark:border-white/10 dark:bg-white/5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">{{ __('What you get here') }}</p>
                    <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)] dark:text-gray-300">
                        {{ __('Every course page shows clear pricing, lesson structure, and direct enrollment so you can compare offers quickly without guessing what happens next.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="cf-shell pb-14 sm:pb-16 lg:pb-20">
        @if ($courses->count())
            <div class="cf-card-grid">
                @foreach ($courses as $course)
                    <x-course.card :course="$course" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $courses->appends(request()->query())->links() }}
            </div>
        @else
            <div class="cf-panel px-8 py-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7l8-4 8 4-8 4-8-4z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                        <path d="M6 10v7a2 2 0 002 2h8a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <h2 class="mt-5 text-2xl font-semibold text-[var(--color-text-primary)]">{{ __('No courses match this filter yet') }}</h2>
                <p class="mt-3 text-sm text-[var(--color-text-muted)]">{{ __('Try another filter or return to the full catalog to explore all available courses.') }}</p>
                <div class="mt-8">
                    <a href="{{ route('courses.index') }}" class="cf-button-primary">{{ __('View all courses') }}</a>
                </div>
            </div>
        @endif
    </section>
</x-public-layout>
