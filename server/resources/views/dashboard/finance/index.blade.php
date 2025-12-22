<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
                {{ __('Finance') }}
            </h2>
            <p class="text-sm text-[var(--color-text-muted)]">
                {{ __('Track sales and understand performance at a glance') }}
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 space-y-1">
                <p class="text-sm text-[var(--color-text-muted)]">{{ __('Total Sales (All Time)') }}</p>
                <p class="text-2xl font-bold text-[var(--color-text-primary)]">{{ number_format($all_time_sales, 2) }} USD</p>
                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Across all time') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 space-y-1">
                <p class="text-sm text-[var(--color-text-muted)]">{{ __('Total Sales (This Month)') }}</p>
                <p class="text-2xl font-bold text-[var(--color-text-primary)]">{{ number_format($month_sales, 2) }} USD</p>
                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Recorded this month') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 space-y-1">
                <p class="text-sm text-[var(--color-text-muted)]">{{ __('Best Selling Course') }}</p>
                @if ($best_selling_course)
                    <p class="text-lg font-semibold text-[var(--color-primary)]">{{ $best_selling_course['title'] }}</p>
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('Sales') }}: {{ $best_selling_course['count'] }}</p>
                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Based on paid enrollments') }}</p>
                @else
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('No sales yet.') }}</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6">
            <h3 class="text-lg font-semibold text-[var(--color-text-primary)] mb-3">{{ __('Sales Count per Course') }}</h3>
            @if (!empty($sales_per_course))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[var(--color-secondary)]/20">
                        <thead class="bg-[var(--color-bg)]">
                            <tr>
                                <th class="px-4 py-3 text-start text-xs font-semibold text-[var(--color-text-muted)]">{{ __('Course') }}</th>
                                <th class="px-4 py-3 text-start text-xs font-semibold text-[var(--color-text-muted)]">{{ __('Sales') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-secondary)]/20 bg-white">
                            @foreach ($sales_per_course as $row)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $row['title'] }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $row['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-[var(--color-text-muted)]">{{ __('No sales yet.') }}</p>
            @endif
        </div>
    </div>
</x-app-layout>
