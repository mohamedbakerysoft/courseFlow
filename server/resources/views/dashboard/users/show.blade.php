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
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between p-2">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[var(--color-primary)]/10 text-lg font-bold text-[var(--color-primary)]">
                        {{ Str::substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-lg font-bold tracking-tight text-[var(--color-text-primary)]">{{ $user->name }}</p>
                        <p class="text-sm text-[var(--color-text-muted)]">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="text-left sm:text-right">
                        @if ($user->is_disabled)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[var(--color-error)]/10 text-[var(--color-error)] text-xs font-semibold">{{ __('Disabled') }}</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-500/10 text-green-700 dark:bg-green-500/20 dark:text-green-400 text-xs font-semibold">{{ __('Active') }}</span>
                        @endif
                    </div>
                    <form action="{{ route('dashboard.users.status', $user) }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="is_disabled" value="{{ $user->is_disabled ? 0 : 1 }}">
                        @if ($user->is_disabled)
                            <button type="submit" class="cf-button-primary !py-2 !px-4 text-sm w-full sm:w-auto">
                                {{ __('Activate') }}
                            </button>
                        @else
                            <button type="submit" class="cf-button-secondary !py-2 !px-4 text-sm w-full sm:w-auto">
                                {{ __('Deactivate') }}
                            </button>
                        @endif
                    </form>
                </div>
            </div>
            @if (!$user->is_disabled)
                <p class="mt-4 border-t border-[rgba(15,23,42,0.06)] dark:border-white/[0.06] pt-4 text-xs text-[var(--color-text-muted)]">
                    {{ __('Deactivating a user prevents them from logging in and accessing any course material.') }}
                </p>
            @endif
        </div>

        <div class="cf-admin-form-card space-y-6">
            <div class="cf-admin-section-header">
                <h3 class="cf-admin-section-title">{{ __('Course Access') }}</h3>
                <p class="cf-admin-section-copy">{{ __('Enrolled Courses') }}: {{ $enrolledCount }}</p>
            </div>
                @if ($enrolledCourses->count())
                    <ul class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($enrolledCourses as $c)
                            <li class="flex flex-col justify-between gap-4 rounded-xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.02)] p-4 dark:border-white/[0.08] dark:bg-white/[0.02] sm:flex-row sm:items-center">
                                <div>
                                    <p class="text-sm font-bold text-[var(--color-text-primary)]">{{ $c->title }}</p>
                                    <p class="mt-1 text-xs text-[var(--color-text-muted)]">#{{ $c->slug }}</p>
                                </div>
                                <form action="{{ route('dashboard.users.revoke_access', [$user, $c]) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to revoke access to this course?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition-colors hover:bg-red-100 dark:border-red-900/50 dark:bg-[#280c0c] dark:text-red-400 dark:hover:bg-red-900/40">
                                        {{ __('Revoke Access') }}
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

                <div class="mt-8 border-t border-[rgba(15,23,42,0.08)] pt-6 dark:border-white/[0.08]">
                    <div class="mb-4">
                        <h4 class="text-sm font-bold text-[var(--color-text-primary)]">{{ __('Grant New Course Access') }}</h4>
                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ __('Manually enroll this user into an existing published course.') }}</p>
                    </div>
                    <form action="{{ route('dashboard.users.grant_access', $user) }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:items-start">
                        @csrf
                        <div class="flex-1">
                            <label class="sr-only">{{ __('Select Course') }}</label>
                            <select name="course_id" class="cf-select w-full">
                                <option value="" disabled selected>{{ __('Choose a course...') }}</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="cf-button-primary whitespace-nowrap">
                            {{ __('Grant Access') }}
                        </button>
                    </form>
                </div>
        </div>
    </div>
</x-app-layout>
