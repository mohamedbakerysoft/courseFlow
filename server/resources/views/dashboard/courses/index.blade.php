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
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->count() }}</p>
            </div>
            <div class="cf-stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Drafts') }}</p>
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->where('status', \App\Models\Course::STATUS_DRAFT)->count() }}</p>
            </div>
            <div class="cf-stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Published') }}</p>
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->where('status', \App\Models\Course::STATUS_PUBLISHED)->count() }}</p>
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
                                    <p class="font-semibold text-[var(--color-text-primary)]">{{ $course->title }}</p>
                                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ __('Course overview and learning flow') }}</p>
                                </div>
                            </td>
                            <td class="text-[var(--color-text-muted)]">{{ $course->slug }}</td>
                            <td>
                                @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                    <span class="cf-badge-muted">{{ __('Draft') }}</span>
                                @else
                                    <span class="cf-badge">{{ __('Published') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('dashboard.courses.edit', $course) }}" class="cf-button-ghost !px-4 !py-2">{{ __('Edit') }}</a>
                                    <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="cf-button-ghost !px-4 !py-2">{{ __('Lessons') }}</a>
                                    @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                        <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.publish', $course) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button :disabled="isSubmitting" class="cf-button-primary !px-4 !py-2">{{ __('Publish') }}</button>
                                        </form>
                                    @else
                                        <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.unpublish', $course) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button :disabled="isSubmitting" class="cf-button-secondary !px-4 !py-2 !text-[var(--color-text-primary)]">{{ __('Unpublish') }}</button>
                                        </form>
                                    @endif
                                    <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.destroy', $course) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button :disabled="isSubmitting" class="inline-flex items-center rounded-full bg-[var(--color-error)]/10 px-4 py-2 text-sm font-semibold text-[var(--color-error)] hover:bg-[var(--color-error)]/14">{{ __('Delete') }}</button>
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
    </div>
</x-app-layout>
