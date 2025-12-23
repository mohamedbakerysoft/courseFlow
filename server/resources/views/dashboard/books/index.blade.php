<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Instructor workspace') }}</p>
                <h1 class="cf-dark-title mt-3 text-3xl font-bold tracking-[-0.04em] sm:text-4xl">{{ __('Manage paid and free downloadable books') }}</h1>
                <p class="cf-dark-copy mt-3 max-w-2xl text-sm leading-7">{{ __('Upload download files, set pricing, and publish a polished library beside the course catalog.') }}</p>
            </div>
            <a href="{{ route('dashboard.books.create') }}" class="cf-button-primary">{{ __('Add Book') }}</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Books')]
        ]" />

        <div class="grid gap-5 sm:grid-cols-3">
            <div class="cf-stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Total books') }}</p>
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $books->total() }}</p>
            </div>
            <div class="cf-stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Drafts') }}</p>
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $books->getCollection()->where('status', \App\Models\Course::STATUS_DRAFT)->count() }}</p>
            </div>
            <div class="cf-stat-card">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Free downloads') }}</p>
                <p class="mt-3 text-4xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $books->getCollection()->where('is_free', true)->count() }}</p>
            </div>
        </div>

        <div class="cf-table-shell">
            <table class="cf-table">
                <thead>
                    <tr>
                        <th>{{ __('Book') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('File') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[rgba(15,23,42,0.08)]">
                    @forelse($books as $book)
                        <tr>
                            <td>
                                <div>
                                    <p class="font-semibold text-[var(--color-text-primary)]">{{ $book->title }}</p>
                                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">#{{ $book->slug }}</p>
                                </div>
                            </td>
                            <td class="text-[var(--color-text-muted)]">
                                {{ $book->is_free ? __('Free') : number_format((float) $book->price, 2).' '.strtoupper($book->currency ?: 'USD') }}
                            </td>
                            <td class="text-[var(--color-text-muted)]">
                                {{ $book->download_file_path ? __('Ready') : __('Missing file') }}
                            </td>
                            <td>
                                @if($book->status === \App\Models\Course::STATUS_DRAFT)
                                    <span class="cf-badge-muted">{{ __('Draft') }}</span>
                                @else
                                    <span class="cf-badge">{{ __('Published') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('dashboard.books.edit', $book) }}" class="cf-button-ghost !px-4 !py-2">{{ __('Edit') }}</a>
                                    <a href="{{ route('books.show', $book) }}" class="cf-button-ghost !px-4 !py-2">{{ __('Preview') }}</a>
                                    @if($book->status === \App\Models\Course::STATUS_DRAFT)
                                        <form action="{{ route('dashboard.books.publish', $book) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button class="cf-button-primary !px-4 !py-2">{{ __('Publish') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('dashboard.books.unpublish', $book) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button class="cf-button-secondary !px-4 !py-2">{{ __('Unpublish') }}</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('dashboard.books.destroy', $book) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete book?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="cf-button-ghost !px-4 !py-2">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <p class="text-2xl font-semibold text-[var(--color-text-primary)]">{{ __('You have not created any books yet.') }}</p>
                                <p class="mt-3 text-sm text-[var(--color-text-muted)]">{{ __('Add your first downloadable book to expand the storefront.') }}</p>
                                <div class="mt-6">
                                    <a href="{{ route('dashboard.books.create') }}" class="cf-button-primary">{{ __('Add Book') }}</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
