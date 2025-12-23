<x-public-layout :title="'Courses'" :metaDescription="'Browse published courses'">
    @php
        $catalogCourses = $courses->getCollection();
        $freeCourses = $catalogCourses->where('is_free', true)->count();
        $paidCourses = $catalogCourses->where('is_free', false)->count();
        $arabicCourses = $catalogCourses->where('language', 'ar')->count();
    @endphp

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
                    <span class="cf-chip">{{ __('Free courses: :count', ['count' => $freeCourses]) }}</span>
                    <span class="cf-chip">{{ __('Paid courses: :count', ['count' => $paidCourses]) }}</span>
                    @if ($arabicCourses > 0)
                        <span class="cf-chip">{{ __('Arabic-ready options') }}</span>
                    @endif
                    <span class="cf-chip">{{ __('Instant enrollment') }}</span>
                </div>
            </div>
            <div class="cf-panel px-6 py-6 sm:px-7">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->total() }}</p>
                        <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Published courses') }}</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $catalogCourses->sum('lessons_count') }}</p>
                        <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Visible lessons on this page') }}</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Simple') }}</p>
                        <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Clear pricing, trusted checkout, and structured access') }}</p>
                    </div>
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
                {{ $courses->links() }}
            </div>
        @else
            <div class="cf-panel px-8 py-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7l8-4 8 4-8 4-8-4z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                        <path d="M6 10v7a2 2 0 002 2h8a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <h2 class="mt-5 text-2xl font-semibold text-[var(--color-text-primary)]">{{ __('No courses available yet') }}</h2>
                <p class="mt-3 text-sm text-[var(--color-text-muted)]">{{ __('Published courses will appear here once the catalog is ready.') }}</p>
                <div class="mt-8">
                    <a href="{{ url('/') }}" class="cf-button-primary">{{ __('Back to Home') }}</a>
                </div>
            </div>
        @endif
    </section>
</x-public-layout>
