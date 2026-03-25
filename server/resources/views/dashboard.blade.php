<x-app-layout>
    <x-slot name="header">
        @if ($isInstructor)
            <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div class="max-w-3xl">
                    <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Admin workspace') }}</p>
                    <h2 class="cf-dark-title mt-3 text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                        {{ __('See what needs attention and jump straight into the work') }}
                    </h2>
                    <p class="cf-dark-copy mt-3 max-w-2xl text-sm leading-7">
                        {{ __('Track products, lessons, students, and payments from one focused dashboard built for daily admin work instead of decorative filler.') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('dashboard.courses.create') }}" class="cf-button-primary">{{ __('Create course') }}</a>
                    <a href="{{ route('dashboard.finance.manual_payments') }}" class="cf-button-secondary">{{ __('Review manual payments') }}</a>
                </div>
            </div>
        @else
            <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div class="max-w-3xl">
                    <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Student dashboard') }}</p>
                    <h2 class="cf-dark-title mt-3 text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                        {{ __('Your enrolled courses stay organized in one learner view') }}
                    </h2>
                    <p class="cf-dark-copy mt-3 max-w-2xl text-sm leading-7">
                        {{ __('Continue learning, return to your enrolled courses quickly, and browse the catalog whenever you are ready for another course or book.') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('courses.index') }}" class="cf-button-primary">{{ __('Browse Courses') }}</a>
                    <a href="{{ route('books.index') }}" class="cf-button-secondary">{{ __('Browse Books') }}</a>
                </div>
            </div>
        @endif
    </x-slot>

    <div class="space-y-8">
        <x-public.demo-notice />

        @if ($isInstructor)
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
                @foreach ($stats as $stat)
                    <article class="cf-dashboard-stat">
                        <p class="cf-dashboard-stat-label">{{ __($stat['label']) }}</p>
                        <p class="cf-dashboard-stat-value">{{ $stat['value'] }}</p>
                        <p class="cf-dashboard-stat-hint">{{ __($stat['hint']) }}</p>
                    </article>
                @endforeach
            </section>

            <section class="grid gap-6 xl:grid-cols-[0.95fr,1.05fr]">
                <article class="cf-section-shell">
                    <div class="cf-dashboard-section-head">
                        <div>
                            <span class="cf-kicker">{{ __('Needs attention') }}</span>
                            <h3 class="cf-dashboard-section-title">{{ __('Important tasks that should be handled first') }}</h3>
                        </div>
                    </div>

                    @if (count($attentionItems))
                        <div class="cf-dashboard-list mt-6">
                            @foreach ($attentionItems as $item)
                                <article class="cf-dashboard-list-item">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h4 class="cf-dashboard-item-title">{{ __($item['title']) }}</h4>
                                            <span class="cf-badge-muted">{{ __($item['badge']) }}</span>
                                        </div>
                                        <p class="cf-dashboard-item-copy">{{ __($item['description']) }}</p>
                                    </div>
                                    <a href="{{ $item['url'] }}" class="cf-button-secondary">{{ __($item['action']) }}</a>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-6 cf-panel-soft px-6 py-8">
                            <p class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Nothing urgent right now') }}</p>
                            <p class="mt-2 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Your products and payment queue look under control. Use the shortcuts on the right to keep content moving.') }}</p>
                        </div>
                    @endif
                </article>

                <article class="cf-section-shell">
                    <div class="cf-dashboard-section-head">
                        <div>
                            <span class="cf-kicker">{{ __('Quick access') }}</span>
                            <h3 class="cf-dashboard-section-title">{{ __('Jump straight into the most important admin areas') }}</h3>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        @foreach ($quickLinks as $link)
                            <a href="{{ $link['url'] }}" class="cf-dashboard-quick-link">
                                <div>
                                    <div class="flex items-center justify-between gap-4">
                                        <h4 class="cf-dashboard-item-title">{{ __($link['title']) }}</h4>
                                        <span class="cf-badge-muted">{{ __($link['meta']) }}</span>
                                    </div>
                                    <p class="cf-dashboard-item-copy">{{ __($link['description']) }}</p>
                                </div>
                                <span class="cf-dashboard-link-arrow" aria-hidden="true">&rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </article>
            </section>

            <section class="grid gap-6 xl:grid-cols-3">
                <article class="cf-section-shell xl:col-span-1">
                    <div class="cf-dashboard-section-head">
                        <div>
                            <span class="cf-kicker">{{ __('Draft queue') }}</span>
                            <h3 class="cf-dashboard-section-title">{{ __('Unpublished work') }}</h3>
                        </div>
                    </div>

                    @if (count($draftItems))
                        <div class="cf-dashboard-list mt-6">
                            @foreach ($draftItems as $item)
                                <article class="cf-dashboard-list-item">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h4 class="cf-dashboard-item-title">{{ $item['title'] }}</h4>
                                            <span class="cf-badge-muted">{{ __($item['badge']) }}</span>
                                        </div>
                                        <p class="cf-dashboard-item-copy">{{ __($item['description']) }}</p>
                                    </div>
                                    <a href="{{ $item['url'] }}" class="cf-button-secondary">{{ __($item['action']) }}</a>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-6 cf-panel-soft px-6 py-8">
                            <p class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('No draft items') }}</p>
                            <p class="mt-2 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Everything currently looks ready or already published.') }}</p>
                        </div>
                    @endif
                </article>

                <article class="cf-section-shell xl:col-span-1">
                    <div class="cf-dashboard-section-head">
                        <div>
                            <span class="cf-kicker">{{ __('Recent products') }}</span>
                            <h3 class="cf-dashboard-section-title">{{ __('Latest course and lesson changes') }}</h3>
                        </div>
                    </div>

                    <div class="cf-dashboard-list mt-6">
                        @forelse ($recentProducts as $item)
                            <article class="cf-dashboard-list-item">
                                <div class="min-w-0">
                                    <h4 class="cf-dashboard-item-title">{{ $item['title'] }}</h4>
                                    <p class="cf-dashboard-item-copy">{{ __($item['description']) }}</p>
                                    <p class="cf-dashboard-item-meta">{{ $item['meta'] }}</p>
                                </div>
                                <a href="{{ $item['url'] }}" class="cf-button-secondary">{{ __($item['action']) }}</a>
                            </article>
                        @empty
                            <p class="text-sm text-[var(--color-text-muted)]">{{ __('No products updated yet.') }}</p>
                        @endforelse
                    </div>

                    <div class="cf-dashboard-subsection">
                        <h4 class="cf-dashboard-subsection-title">{{ __('Recent lessons') }}</h4>
                        <div class="cf-dashboard-list">
                            @forelse ($recentLessons as $item)
                                <article class="cf-dashboard-list-item">
                                    <div class="min-w-0">
                                        <h4 class="cf-dashboard-item-title">{{ $item['title'] }}</h4>
                                        <p class="cf-dashboard-item-copy">{{ __($item['description']) }}</p>
                                        <p class="cf-dashboard-item-meta">{{ $item['meta'] }}</p>
                                    </div>
                                    <a href="{{ $item['url'] }}" class="cf-button-secondary">{{ __($item['action']) }}</a>
                                </article>
                            @empty
                                <p class="text-sm text-[var(--color-text-muted)]">{{ __('No lesson updates yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </article>

                <article class="cf-section-shell xl:col-span-1">
                    <div class="cf-dashboard-section-head">
                        <div>
                            <span class="cf-kicker">{{ __('People and payments') }}</span>
                            <h3 class="cf-dashboard-section-title">{{ __('Recent student and finance activity') }}</h3>
                        </div>
                    </div>

                    <div class="cf-dashboard-subsection mt-6">
                        <h4 class="cf-dashboard-subsection-title">{{ __('New students') }}</h4>
                        <div class="cf-dashboard-list">
                            @forelse ($recentStudents as $item)
                                <article class="cf-dashboard-list-item">
                                    <div class="min-w-0">
                                        <h4 class="cf-dashboard-item-title">{{ $item['title'] }}</h4>
                                        <p class="cf-dashboard-item-copy">{{ $item['description'] }}</p>
                                        <p class="cf-dashboard-item-meta">{{ $item['meta'] }}</p>
                                    </div>
                                    <a href="{{ $item['url'] }}" class="cf-button-secondary">{{ __($item['action']) }}</a>
                                </article>
                            @empty
                                <p class="text-sm text-[var(--color-text-muted)]">{{ __('No new students yet.') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="cf-dashboard-subsection">
                        <h4 class="cf-dashboard-subsection-title">{{ __('Recent manual payment requests') }}</h4>
                        <div class="cf-dashboard-list">
                            @forelse ($recentManualRequests as $item)
                                <article class="cf-dashboard-list-item">
                                    <div class="min-w-0">
                                        <h4 class="cf-dashboard-item-title">{{ $item['title'] }}</h4>
                                        <p class="cf-dashboard-item-copy">{{ __($item['description']) }}</p>
                                        <p class="cf-dashboard-item-meta">{{ $item['meta'] }}</p>
                                    </div>
                                    <a href="{{ $item['url'] }}" class="cf-button-secondary">{{ __($item['action']) }}</a>
                                </article>
                            @empty
                                <p class="text-sm text-[var(--color-text-muted)]">{{ __('No manual payment requests yet.') }}</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="cf-dashboard-subsection">
                        <h4 class="cf-dashboard-subsection-title">{{ __('Recent paid orders') }}</h4>
                        <div class="cf-dashboard-list">
                            @forelse ($recentPaidPayments as $item)
                                <article class="cf-dashboard-list-item">
                                    <div class="min-w-0">
                                        <h4 class="cf-dashboard-item-title">{{ $item['title'] }}</h4>
                                        <p class="cf-dashboard-item-copy">{{ __($item['description']) }}</p>
                                        <p class="cf-dashboard-item-meta">{{ $item['meta'] }}</p>
                                    </div>
                                    <a href="{{ $item['url'] }}" class="cf-button-secondary">{{ __($item['action']) }}</a>
                                </article>
                            @empty
                                <p class="text-sm text-[var(--color-text-muted)]">{{ __('No paid orders yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </article>
            </section>
        @else
            <section class="cf-section-shell">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <span class="cf-kicker">{{ __('Student dashboard') }}</span>
                        <h3 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Your enrolled courses stay organized in one learner view') }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Browse what you have enrolled in, continue lessons, and return to the catalog when you are ready for more.') }}</p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="cf-button-primary">{{ __('Browse Courses') }}</a>
                </div>

                @if (!empty($enrolledCourses) && $enrolledCourses->count())
                    <div class="mt-8 cf-card-grid">
                        @foreach ($enrolledCourses as $course)
                            <x-course.card :course="$course" ctaLabel="Continue" />
                        @endforeach
                    </div>
                @else
                    <div class="mt-8 cf-panel-soft px-8 py-10 text-center">
                        <p class="text-2xl font-bold text-[var(--color-text-primary)]">{{ __('No enrollments yet') }}</p>
                        <p class="mt-3 text-sm text-[var(--color-text-muted)]">{{ __('Browse courses to start building your learning library.') }}</p>
                    </div>
                @endif
            </section>
        @endif
    </div>
</x-app-layout>
