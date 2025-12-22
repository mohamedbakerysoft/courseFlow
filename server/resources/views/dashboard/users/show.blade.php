<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
                {{ __('User Details') }}
            </h2>
            <p class="text-sm text-[var(--color-text-muted)]">
                {{ __('Review status and course access. Toggle Active/Disabled and grant access.') }}
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-[var(--color-secondary)]/10">
            <div class="p-6">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-[var(--color-accent)]/10 text-[var(--color-accent)] text-xs font-semibold">{{ __('Active') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <form action="{{ route('dashboard.users.status', $user) }}" method="POST" class="inline-flex gap-2">
                        @csrf
                        <input type="hidden" name="is_disabled" value="{{ $user->is_disabled ? 0 : 1 }}">
                        @if ($user->is_disabled)
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-accent)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-accent)]">
                                {{ __('Activate') }}
                            </button>
                        @else
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-error)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-error)]">
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

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-[var(--color-secondary)]/10">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-[var(--color-text-primary)] mb-3">{{ __('Course Access') }}</h3>
                <p class="text-sm text-[var(--color-text-muted)] mb-4">{{ __('Enrolled Courses') }}: {{ $enrolledCount }}</p>
                @if ($enrolledCourses->count())
                    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($enrolledCourses as $c)
                            <li class="border border-[var(--color-secondary)]/20 rounded-lg p-3">
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ $c->title }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">#{{ $c->slug }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="rounded-lg border border-dashed border-[var(--color-secondary)]/30 p-6 text-center">
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

                <div class="mt-6">
                    <form action="{{ route('dashboard.users.grant_access', $user) }}" method="POST" class="flex items-end gap-3">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-[var(--color-text-primary)] mb-1">{{ __('Grant Access to Course') }}</label>
                            <select name="course_id" class="w-full rounded-md border border-[var(--color-secondary)]/30 text-sm text-[var(--color-text-primary)] bg-white focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                            {{ __('Grant Access') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
