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
                                    <p class="font-semibold text-sm text-[var(--color-text-primary)] leading-tight">{{ $book->title }}</p>
                                    <p class="mt-0.5 text-[11px] font-mono text-[var(--color-text-muted)] opacity-80">#{{ $book->slug }}</p>
                                </div>
                            </td>
                            <td class="text-[var(--color-text-muted)] text-sm">
                                {{ $book->is_free ? __('Free') : number_format((float) $book->price, 2).' '.strtoupper($book->currency ?: 'USD') }}
                            </td>
                            <td class="text-[var(--color-text-muted)] text-sm">
                                {{ $book->download_file_path ? __('Ready') : __('Missing file') }}
                            </td>
                            <td>
                                @if($book->status === \App\Models\Course::STATUS_DRAFT)
                                    <span class="cf-badge-muted !py-1 !px-2 text-[10px]">{{ __('Draft') }}</span>
                                @else
                                    <span class="cf-badge !py-1 !px-2 text-[10px]">{{ __('Published') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.books.edit', $book) }}" class="cf-button-secondary !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold">{{ __('Edit') }}</a>
                                    <a href="{{ route('books.show', $book) }}" target="_blank" class="cf-button-secondary !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold">{{ __('Preview') }}</a>
                                    @if($book->status === \App\Models\Course::STATUS_DRAFT)
                                        <form action="{{ route('dashboard.books.publish', $book) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button class="cf-button-primary !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold">{{ __('Publish') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('dashboard.books.unpublish', $book) }}" method="POST" class="inline-flex">
                                            @csrf
                                            <button class="cf-button-secondary !text-amber-600 border border-amber-200 dark:border-amber-900 !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold hover:!bg-amber-50 dark:hover:!bg-amber-900/20">{{ __('Unpublish') }}</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('dashboard.books.destroy', $book) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete book?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="cf-button-secondary !text-red-500 border border-red-200 dark:border-red-900 !px-3 !py-1 text-[11px] uppercase tracking-wider font-bold hover:!bg-red-50 dark:hover:!bg-red-900/20">{{ __('Delete') }}</button>
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

        @if ($books->hasPages())
            <div class="flex flex-col gap-4 rounded-[22px] border border-[rgba(11,11,11,0.08)] bg-white px-5 py-4 shadow-[0_10px_24px_rgba(11,11,11,0.03)] sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-[var(--color-text-muted)]">
                    {{ __('Showing :from-:to of :total books', ['from' => $books->firstItem(), 'to' => $books->lastItem(), 'total' => $books->total()]) }}
                </p>

                <nav aria-label="{{ __('Books pagination') }}" class="flex flex-wrap items-center gap-2">
                    @if ($books->onFirstPage())
                        <span class="inline-flex min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-4 py-2 text-sm font-semibold text-[var(--color-text-muted)] opacity-60">{{ __('Previous') }}</span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}" class="inline-flex min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-4 py-2 text-sm font-semibold text-[var(--color-text-primary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">{{ __('Previous') }}</a>
                    @endif

                    @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                        @if ($page === $books->currentPage())
                            <span aria-current="page" class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-[var(--color-primary)] px-3 text-sm font-semibold text-[var(--color-secondary)]">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-3 text-sm font-semibold text-[var(--color-text-primary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}" class="inline-flex min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-4 py-2 text-sm font-semibold text-[var(--color-text-primary)] transition hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">{{ __('Next') }}</a>
                    @else
                        <span class="inline-flex min-w-10 items-center justify-center rounded-full border border-[rgba(11,11,11,0.08)] px-4 py-2 text-sm font-semibold text-[var(--color-text-muted)] opacity-60">{{ __('Next') }}</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</x-app-layout>
