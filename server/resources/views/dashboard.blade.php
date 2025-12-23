<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Instructor workspace') }}</p>
                <h2 class="cf-dark-title mt-3 text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                    {{ __('Manage your course business from one clean workspace') }}
                </h2>
                <p class="cf-dark-copy mt-3 text-sm leading-7">
                    {{ __('Track courses, students, payments, and drafts without losing the clarity of the public storefront.') }}
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3">
                <div class="cf-dark-soft-card rounded-[24px] px-4 py-4 backdrop-blur">
                    <p class="cf-dark-muted text-xs uppercase tracking-[0.22em]">{{ __('Courses') }}</p>
                    <p class="cf-dark-title mt-2 text-2xl font-bold">{{ $totalCourses ?? ($enrolledCourses->count() ?? 0) }}</p>
                </div>
                <div class="cf-dark-soft-card rounded-[24px] px-4 py-4 backdrop-blur">
                    <p class="cf-dark-muted text-xs uppercase tracking-[0.22em]">{{ __('Students') }}</p>
                    <p class="cf-dark-title mt-2 text-2xl font-bold">{{ $totalStudents ?? 0 }}</p>
                </div>
                <div class="cf-dark-soft-card rounded-[24px] px-4 py-4 backdrop-blur">
                    <p class="cf-dark-muted text-xs uppercase tracking-[0.22em]">{{ __('Lessons') }}</p>
                    <p class="cf-dark-title mt-2 text-2xl font-bold">{{ $totalLessons ?? 0 }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <x-public.demo-notice />

        @can('viewAny', \App\Models\Course::class)
            <section class="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">
                <div class="cf-panel px-6 py-6 sm:px-8">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <span class="cf-kicker">{{ __('Quick actions') }}</span>
                            <h3 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Create, review, and publish from one place') }}</h3>
                            <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Create content, preview learner-facing pages, and move from drafts to launch without losing context.') }}</p>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('dashboard.courses.create') }}" class="cf-button-primary">{{ __('Create Course') }}</a>
                            <a href="{{ route('dashboard.courses.index') }}" class="cf-button-ghost">{{ __('Manage Courses') }}</a>
                        </div>
                    </div>
                </div>

                <div class="cf-panel-dark px-6 py-6">
                    <p class="cf-dark-muted text-xs font-semibold uppercase tracking-[0.24em]">{{ __('Product status') }}</p>
                    <h3 class="cf-dark-title mt-3 text-2xl font-bold tracking-[-0.04em]">{{ __('Instructor workflow at a glance') }}</h3>
                    <ul class="cf-dark-copy mt-5 space-y-3 text-sm leading-7">
                        <li>{{ __('Review important metrics before diving into daily tasks.') }}</li>
                        <li>{{ __('Move quickly between course creation, lesson updates, and previews.') }}</li>
                        <li>{{ __('Keep the dashboard visually aligned with the public storefront.') }}</li>
                    </ul>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="cf-stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Latest draft course') }}</p>
                            <h3 class="mt-3 text-xl font-bold text-[var(--color-text-primary)]">{{ $latestDraftCourse?->title ?? __('No draft courses yet.') }}</h3>
                        </div>
                        <span class="cf-badge-muted">{{ __('Draft') }}</span>
                    </div>
                    @if (!empty($latestDraftCourse))
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('dashboard.courses.edit', $latestDraftCourse) }}" class="cf-button-secondary">{{ __('Edit') }}</a>
                            <a href="{{ route('courses.show', $latestDraftCourse) }}" target="_blank" rel="noopener" class="cf-button-primary">{{ __('Preview') }}</a>
                        </div>
                    @endif
                </div>

                <div class="cf-stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Latest draft lesson') }}</p>
                            <h3 class="mt-3 text-xl font-bold text-[var(--color-text-primary)]">{{ $latestDraftLesson?->title ?? __('No draft lessons yet.') }}</h3>
                        </div>
                        <span class="cf-badge-muted">{{ __('Draft') }}</span>
                    </div>
                    @if (!empty($latestDraftLesson))
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('dashboard.lessons.edit', $latestDraftLesson) }}" class="cf-button-secondary">{{ __('Edit') }}</a>
                            <a href="{{ route('courses.show', $latestDraftLesson->course) }}" target="_blank" rel="noopener" class="cf-button-primary">{{ __('Preview') }}</a>
                        </div>
                    @endif
                </div>
            </section>

            <section class="grid gap-6 sm:grid-cols-3">
                <div class="cf-stat-card">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Total Courses') }}</p>
                    <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $totalCourses }}</p>
                </div>
                <div class="cf-stat-card">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Total Students') }}</p>
                    <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $totalStudents }}</p>
                </div>
                <div class="cf-stat-card">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Total Lessons') }}</p>
                    <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $totalLessons }}</p>
                </div>
            </section>
        @else
            <section class="cf-panel px-6 py-6 sm:px-8">
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
        @endcan
    </div>
</x-app-layout>
