<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Instructor workspace') }}</p>
                <h1 class="cf-dark-title mt-3 text-3xl font-bold tracking-[-0.04em] sm:text-4xl">{{ __('Manage every course from one organized table') }}</h1>
                <p class="cf-dark-copy mt-3 max-w-2xl text-sm leading-7">{{ __('Review publishing status, open lessons, and update the catalog without leaving the instructor workspace.') }}</p>
            </div>
            <a href="{{ route('dashboard.courses.create') }}" class="cf-button-primary">{{ __('Add Course') }}</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Courses')]
        ]" />

        <div class="grid gap-5 sm:grid-cols-3">
            <div class="cf-stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Total courses') }}</p>
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->total() }}</p>
            </div>
            <div class="cf-stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Drafts') }}</p>
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->getCollection()->where('status', \App\Models\Course::STATUS_DRAFT)->count() }}</p>
            </div>
            <div class="cf-stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Published') }}</p>
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->getCollection()->where('status', \App\Models\Course::STATUS_PUBLISHED)->count() }}</p>
            </div>
        </div>

        <div class="cf-table-shell">
            <table class="cf-table">
                <thead>
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[rgba(15,23,42,0.08)]">
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                <div>
                                    <p class="font-semibold text-sm text-[var(--color-text-primary)] leading-tight">{{ $course->title }}</p>
                                </div>
                            </td>
                            <td class="text-[var(--color-text-muted)] text-sm">{{ $course->slug }}</td>
                            <td>
                                @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                    <span class="cf-badge-muted !py-1 !px-2 text-[10px]">{{ __('Draft') }}</span>
                                @else
                                    <span class="cf-badge !py-1 !px-2 text-[10px]">{{ __('Published') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.courses.edit', $course) }}" class="cf-button-secondary !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold">{{ __('Edit') }}</a>
                                    <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="cf-button-secondary !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold">{{ __('Lessons') }}</a>
                                    @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                        <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.publish', $course) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button :disabled="isSubmitting" class="cf-button-primary !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold">{{ __('Publish') }}</button>
                                        </form>
                                    @else
                                        <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.unpublish', $course) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button :disabled="isSubmitting" class="cf-button-secondary !text-amber-600 border border-amber-200 dark:border-amber-900 !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold hover:!bg-amber-50 dark:hover:!bg-amber-900/20">{{ __('Unpublish') }}</button>
                                        </form>
                                    @endif
                                    <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.destroy', $course) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button :disabled="isSubmitting" class="cf-button-secondary !text-red-500 border border-red-200 dark:border-red-900 !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold hover:!bg-red-50 dark:hover:!bg-red-900/20">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-14 text-center">
                                <p class="text-2xl font-semibold text-[var(--color-text-primary)]">{{ __('You have not created any courses yet.') }}</p>
                                <p class="mt-3 text-sm text-[var(--color-text-muted)]">{{ __('Create your first course to start selling.') }}</p>
                                <div class="mt-6">
                                    <a href="{{ route('dashboard.courses.create') }}" class="cf-button-primary">{{ __('Add Course') }}</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($courses->hasPages())
            <div class="flex flex-col gap-4 rounded-[22px] border border-[rgba(11,11,11,0.08)] bg-white px-5 py-4 shadow-[0_10px_24px_rgba(11,11,11,0.03)] sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-[var(--color-text-muted)]">
                    {{ __('Showing :from-:to of :total courses', ['from' => $courses->firstItem(), 'to' => $courses->lastItem(), 'total' => $courses->total()]) }}
                </p>

                <nav aria-label="{{ __('Courses pagination') }}" class="flex flex-wrap items-center gap-2">
                    @if ($courses->onFirstPage())
                        <span class="inline-flex min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-4 py-2 text-sm font-semibold text-[var(--color-text-muted)] opacity-60">{{ __('Previous') }}</span>
                    @else
                        <a href="{{ $courses->previousPageUrl() }}" class="inline-flex min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-4 py-2 text-sm font-semibold text-[var(--color-text-primary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">{{ __('Previous') }}</a>
                    @endif

                    @foreach ($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
                        @if ($page === $courses->currentPage())
                            <span aria-current="page" class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-[var(--color-primary)] px-3 text-sm font-semibold text-[var(--color-secondary)]">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-3 text-sm font-semibold text-[var(--color-text-primary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($courses->hasMorePages())
                        <a href="{{ $courses->nextPageUrl() }}" class="inline-flex min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-4 py-2 text-sm font-semibold text-[var(--color-text-primary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">{{ __('Next') }}</a>
                    @else
                        <span class="inline-flex min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-4 py-2 text-sm font-semibold text-[var(--color-text-muted)] opacity-60">{{ __('Next') }}</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</x-app-layout>
