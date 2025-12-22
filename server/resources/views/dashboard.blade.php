<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <p class="text-sm text-[var(--color-text-muted)]">
            {{ __('All core modules are configured and ready for real usage.') }}
        </p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <x-public.demo-notice />
            @can('viewAny', \App\Models\Course::class)
                <section aria-label="{{ __('Quick Actions') }}" class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="space-y-1">
                            <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Quick Actions') }}</h3>
                            <p class="text-sm text-[var(--color-text-muted)]">{{ __('Create and manage content faster.') }}</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ app()->getLocale() === 'ar' ? 'بعض الخصائص المتقدمة غير مفعلة في النسخة التجريبية.' : 'Some advanced features are disabled in demo mode.' }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('dashboard.courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                {{ __('Create Course') }}
                            </a>
                            <a href="{{ route('dashboard.courses.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-white border border-[var(--color-secondary)]/30 text-sm font-semibold text-[var(--color-text-primary)] shadow-sm hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7l8-4 8 4-8 4-8-4z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M6 10v7a2 2 0 002 2h8a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                {{ __('Create Lesson') }}
                            </a>
                        </div>
                    </div>
                </section>

                <section aria-label="{{ __('Drafts Preview') }}" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Latest Draft Course') }}</h3>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[var(--color-secondary)]/10 text-[var(--color-secondary)] text-xs font-semibold">{{ __('Draft') }}</span>
                        </div>
                        @if (!empty($latestDraftCourse))
                            <p class="text-sm text-[var(--color-text-primary)] mb-4">{{ $latestDraftCourse->title }}</p>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('dashboard.courses.edit', $latestDraftCourse) }}" class="inline-flex items-center px-4 py-2 rounded-md border border-[var(--color-secondary)]/20 bg-white text-sm font-medium text-[var(--color-secondary)] hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                    {{ __('Edit') }}
                                </a>
                                <a href="{{ route('courses.show', $latestDraftCourse) }}" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                    {{ __('Preview') }}
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-[var(--color-text-muted)]">{{ __('No draft courses yet.') }}</p>
                        @endif
                    </div>
                    <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Latest Draft Lesson') }}</h3>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[var(--color-secondary)]/10 text-[var(--color-secondary)] text-xs font-semibold">{{ __('Draft') }}</span>
                        </div>
                        @if (!empty($latestDraftLesson))
                            <p class="text-sm text-[var(--color-text-primary)] mb-4">{{ $latestDraftLesson->title }}</p>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('dashboard.lessons.edit', $latestDraftLesson) }}" class="inline-flex items-center px-4 py-2 rounded-md border border-[var(--color-secondary)]/20 bg-white text-sm font-medium text-[var(--color-secondary)] hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                    {{ __('Edit') }}
                                </a>
                                <a href="{{ route('courses.show', $latestDraftLesson->course) }}" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                    {{ __('Preview') }}
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-[var(--color-text-muted)]">{{ __('No draft lessons yet.') }}</p>
                        @endif
                    </div>
                </section>

                <section aria-label="{{ __('Stats') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6">
                        <p class="text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wide">{{ __('Total Courses') }}</p>
                        <p class="mt-2 text-3xl font-semibold text-[var(--color-text-primary)]">{{ $totalCourses }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6">
                        <p class="text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wide">{{ __('Total Students') }}</p>
                        <p class="mt-2 text-3xl font-semibold text-[var(--color-text-primary)]">{{ $totalStudents }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6">
                        <p class="text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wide">{{ __('Total Lessons') }}</p>
                        <p class="mt-2 text-3xl font-semibold text-[var(--color-text-primary)]">{{ $totalLessons }}</p>
                    </div>
                </section>
                <section aria-label="{{ __('Completion') }}" class="rounded-xl border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 p-4">
                    <p class="text-sm text-[var(--color-text-muted)]">
                        {{ __('You’ve explored the core workflow. The full version is designed for real instruction and student management.') }}
                    </p>
                    <p class="mt-1 text-xs text-[var(--color-text-muted)]">
                        {{ __('The rest is about scaling, not learning.') }}
                    </p>
                </section>
            @else
                <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-[var(--color-text-primary)] mb-4">{{ __('My Courses') }}</h3>
                        @if (!empty($enrolledCourses) && $enrolledCourses->count())
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($enrolledCourses as $course)
                                    <x-course.card :course="$course" ctaLabel="Continue" />
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-[var(--color-text-primary)]">
                                <div class="text-4xl mb-3">🎓</div>
                                <p class="font-medium">{{ __('No enrollments yet — this is where real enrollments will appear.') }}</p>
                                <p class="text-sm text-[var(--color-text-muted)] mt-1">{{ __('Browse courses to get started.') }}</p>
                                <div class="mt-4">
                                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">{{ __('Browse Courses') }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            @endcan
        </div>
    </div>
</x-app-layout>
