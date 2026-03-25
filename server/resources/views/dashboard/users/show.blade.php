<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('User management') }}</p>
            <h2 class="cf-dark-title text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                {{ __('User Details') }}
            </h2>
            <p class="cf-dark-copy max-w-2xl text-sm leading-7">
                {{ __('Review status and course access. Toggle Active/Disabled and grant access.') }}
            </p>
        </div>
    </x-slot>

    <div class="cf-admin-shell">
        <div class="cf-admin-form-card">
            <div class="cf-admin-toolbar">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[var(--color-text-muted)]">{{ __('Name') }}</p>
                        <p class="text-lg font-semibold text-[var(--color-text-primary)]">{{ $user->name }}</p>
                        <p class="text-sm text-[var(--color-text-muted)]">{{ $user->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-[var(--color-text-muted)]">{{ __('Status') }}</p>
                        @if ($user->is_disabled)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-[var(--color-error)]/10 text-[var(--color-error)] text-xs font-semibold">{{ __('Disabled') }}</span>
                        @else
                            <span class="cf-badge">{{ __('Active') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <form action="{{ route('dashboard.users.status', $user) }}" method="POST" class="inline-flex gap-2">
                        @csrf
                        <input type="hidden" name="is_disabled" value="{{ $user->is_disabled ? 0 : 1 }}">
                        @if ($user->is_disabled)
                            <button type="submit" class="cf-button-primary">
                                {{ __('Activate') }}
                            </button>
                        @else
                            <button type="submit" class="cf-button-secondary">
                                {{ __('Deactivate') }}
                            </button>
                        @endif
                    </form>
                    <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                        {{ __('Deactivating a user removes course access; data remains.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="cf-admin-form-card space-y-6">
            <div class="cf-admin-section-header">
                <h3 class="cf-admin-section-title">{{ __('Course Access') }}</h3>
                <p class="cf-admin-section-copy">{{ __('Enrolled Courses') }}: {{ $enrolledCount }}</p>
            </div>
                @if ($enrolledCourses->count())
                    <ul class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($enrolledCourses as $c)
                            <li class="cf-admin-inline-note flex justify-between items-center group">
                                <div>
                                    <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ $c->title }}</p>
                                    <p class="text-xs text-[var(--color-text-muted)]">#{{ $c->slug }}</p>
                                </div>
                                <form action="{{ route('dashboard.users.revoke_access', [$user, $c]) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to revoke access to this course?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-[var(--color-error)] opacity-0 transition-opacity group-hover:opacity-100 hover:opacity-80">
                                        {{ __('Revoke') }}
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="rounded-[22px] border border-dashed border-[var(--color-secondary)]/24 p-6 text-center">
                        <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-[var(--color-secondary)]/10 text-[var(--color-text-muted)]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="4" y="5" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5" />
                                <path d="M8 9h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                        <p class="text-[var(--color-text-muted)] font-medium">
                            {{ __('No enrolled courses yet.') }}
                        </p>
                        <p class="text-[var(--color-text-muted)] text-sm">
                            {{ __('Grant access below to enroll the user in a course.') }}
                        </p>
                    </div>
                @endif

                <div class="pt-2">
                    <form action="{{ route('dashboard.users.grant_access', $user) }}" method="POST" class="flex flex-col gap-4 lg:flex-row lg:items-end">
                        @csrf
                        <div class="cf-admin-field flex-1">
                            <label>{{ __('Grant Access to Course') }}</label>
                            <select name="course_id" class="cf-select w-full">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="cf-button-primary">
                            {{ __('Grant Access') }}
                        </button>
                    </form>
                </div>
        </div>
    </div>
</x-app-layout>
