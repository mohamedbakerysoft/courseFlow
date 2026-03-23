<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Revenue') }}</p>
            <h2 class="cf-dark-title text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                {{ __('Finance') }}
            </h2>
            <p class="cf-dark-copy max-w-2xl text-sm leading-7">
                {{ __('Track sales and understand performance at a glance') }}
            </p>
        </div>
    </x-slot>

    <div class="cf-admin-shell">
        @include('dashboard.finance._subnav')

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="cf-stat-card space-y-2">
                <p class="text-sm text-[var(--color-text-muted)]">{{ __('Total Sales (All Time)') }}</p>
                <p class="text-2xl font-bold text-[var(--color-text-primary)]">{{ number_format($all_time_sales, 2) }} USD</p>
                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Across all time') }}</p>
                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Refunded / canceled payments are excluded') }}</p>
            </div>
            <div class="cf-stat-card space-y-2">
                <p class="text-sm text-[var(--color-text-muted)]">{{ __('Total Sales (This Month)') }}</p>
                <p class="text-2xl font-bold text-[var(--color-text-primary)]">{{ number_format($month_sales, 2) }} USD</p>
                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Recorded this month') }}</p>
                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Refunded / canceled payments are excluded') }}</p>
            </div>
            <div class="cf-stat-card space-y-2">
                <p class="text-sm text-[var(--color-text-muted)]">{{ __('Best Selling Course') }}</p>
                @if ($best_selling_course)
                    <p class="text-lg font-semibold text-[var(--color-primary)]">{{ $best_selling_course['title'] }}</p>
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('Sales') }}: {{ $best_selling_course['count'] }}</p>
                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Based on paid enrollments') }}</p>
                @else
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('No payments recorded yet — this is where confirmed transactions will appear.') }}</p>
                @endif
                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Refunded / canceled payments are excluded') }}</p>
            </div>
        </div>

        <div class="cf-table-shell">
            <div class="cf-table-shell-header">
                <div>
                    <h3 class="cf-table-shell-title">{{ __('Sales Count per Course') }}</h3>
                    <p class="cf-table-shell-copy">{{ __('A cleaner revenue breakdown by course title with less visual noise and clearer scanning.') }}</p>
                </div>
            </div>
            @if ($sales_per_course->count())
                <div class="overflow-x-auto">
                    <table class="cf-table">
                        <thead>
                            <tr>
                                <th>{{ __('Course') }}</th>
                                <th>{{ __('Sales') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[rgba(15,23,42,0.08)] bg-white">
                            @foreach ($sales_per_course as $row)
                                <tr>
                                    <td>
                                        <div>
                                            <p class="font-semibold text-[var(--color-text-primary)]">{{ $row->title }}</p>
                                            <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ __('Confirmed enrollments only') }}</p>
                                        </div>
                                    </td>
                                    <td class="text-[var(--color-text-muted)]">{{ (int) $row->cnt }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($sales_per_course->hasPages())
                    <div class="mt-4">
                        {{ $sales_per_course->links() }}
                    </div>
                @endif
            @else
                <div class="cf-table-empty">
                    <p class="cf-table-empty-title">{{ __('No payments recorded yet') }}</p>
                    <p class="cf-table-empty-copy">{{ __('Confirmed transactions will appear here as soon as the storefront starts receiving orders.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
