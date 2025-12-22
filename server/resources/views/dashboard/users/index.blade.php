<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ring-1 ring-[var(--color-secondary)]/10">
            <div class="p-6 text-[var(--color-text-primary)]">
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-[var(--color-error)]/10 text-[var(--color-error)] text-xs font-semibold">{{ __('Disabled') }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-[var(--color-accent)]/10 text-[var(--color-accent)] text-xs font-semibold">{{ __('Active') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ \App\Models\User::find($u->id)->courses()->count() }}</td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        <a href="{{ route('dashboard.users.show', $u) }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-[var(--color-primary)] text-white text-xs font-semibold hover:bg-[var(--color-primary-hover)]">{{ __('View') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

