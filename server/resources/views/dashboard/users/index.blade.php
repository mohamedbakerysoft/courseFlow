<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Audience') }}</p>
            <h2 class="cf-dark-title text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                {{ __('Users') }}
            </h2>
            <p class="cf-dark-copy max-w-2xl text-sm leading-7">
                {{ __('Manage users and grant course access') }}
            </p>
        </div>
    </x-slot>

    <div class="cf-admin-shell">
        <div class="cf-table-shell">
            <div class="cf-table-shell-header">
                <div>
                    <h3 class="cf-table-shell-title">{{ __('Registered users') }}</h3>
                    <p class="cf-table-shell-copy">{{ __('Review enrollment status, open user details, and manage access from one premium table.') }}</p>
                </div>
            </div>
            <div class="text-[var(--color-text-primary)]">
                @if ($users->count())
                    <div class="overflow-x-auto">
                        <table class="cf-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Enrolled Courses') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[rgba(15,23,42,0.08)] bg-white">
                                @foreach ($users as $u)
                                    <tr>
                                        <td>
                                            <div>
                                                <p class="font-semibold text-[var(--color-text-primary)]">{{ $u->name }}</p>
                                                <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ __('Learner account') }}</p>
                                            </div>
                                        </td>
                                        <td class="text-[var(--color-text-muted)]">{{ $u->email }}</td>
                                        <td>
                                            @if ($u->is_disabled)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[var(--color-error)]/10 text-[var(--color-error)] text-xs font-semibold">
                                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>
                                                    {{ __('Disabled') }}
                                                </span>
                                            @else
                                                <span class="cf-badge">
                                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>
                                                    {{ __('Active') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-[var(--color-text-muted)]">
                                            {{ \App\Models\User::find($u->id)->courses()->count() }}
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('dashboard.users.show', $u) }}" class="cf-button-primary !px-4 !py-2.5 !text-sm">
                                                {{ __('View details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="cf-table-empty">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-[var(--color-secondary)]/10 text-[var(--color-text-muted)]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 12a5 5 0 100-10 5 5 0 000 10z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M4 22a8 8 0 1116 0H4z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <p class="cf-table-empty-title">
                            {{ __('No users found') }}
                        </p>
                        <p class="cf-table-empty-copy">
                            {{ __('Users will appear here as they register or are added.') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
