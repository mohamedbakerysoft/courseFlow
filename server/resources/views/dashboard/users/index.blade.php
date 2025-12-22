<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
                {{ __('Users') }}
            </h2>
            <p class="text-sm text-[var(--color-text-muted)]">
                {{ __('Manage users and grant course access') }}
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-[var(--color-secondary)]/10">
            <div class="p-6 text-[var(--color-text-primary)]">
                @if ($users->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[var(--color-secondary)]/20">
                            <thead class="bg-[var(--color-bg)]">
                                <tr>
                                    <th class="px-4 py-3 text-start text-xs font-semibold text-[var(--color-text-muted)]">{{ __('Name') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-semibold text-[var(--color-text-muted)]">{{ __('Email') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-semibold text-[var(--color-text-muted)]">{{ __('Status') }}</th>
                                    <th class="px-4 py-3 text-start text-xs font-semibold text-[var(--color-text-muted)]">{{ __('Enrolled Courses') }}</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--color-secondary)]/20 bg-white">
                                @foreach ($users as $u)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ $u->name }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $u->email }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if ($u->is_disabled)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[var(--color-error)]/10 text-[var(--color-error)] text-xs font-semibold">
                                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>
                                                    {{ __('Disabled') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[var(--color-accent)]/10 text-[var(--color-accent)] text-xs font-semibold">
                                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>
                                                    {{ __('Active') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ \App\Models\User::find($u->id)->courses()->count() }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <a href="{{ route('dashboard.users.show', $u) }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-[var(--color-primary)] text-white text-xs font-semibold hover:bg-[var(--color-primary-hover)]">
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
                    <div class="rounded-lg border border-dashed border-[var(--color-secondary)]/30 p-8 text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-[var(--color-secondary)]/10 text-[var(--color-text-muted)]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 12a5 5 0 100-10 5 5 0 000 10z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M4 22a8 8 0 1116 0H4z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <p class="text-[var(--color-text-muted)] font-medium">
                            {{ __('No users found') }}
                        </p>
                        <p class="text-[var(--color-text-muted)] text-sm">
                            {{ __('Users will appear here as they register or are added.') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
