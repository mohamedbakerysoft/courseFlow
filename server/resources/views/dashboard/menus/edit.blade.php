<x-app-layout>
    <div class="cf-admin-shell">
        <div class="space-y-3">
            <x-breadcrumbs :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Menus')],
            ]" />
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="cf-admin-copy text-sm">{{ __('Navigation') }}</p>
                    <h1 class="cf-admin-heading text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Menus') }}</h1>
                    <p class="cf-admin-copy mt-2 max-w-3xl">{{ __('Edit the public header menu labels and reorder them with drag and drop. The header and mobile menu will follow the same order automatically.') }}</p>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="cf-status-message mt-6">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.menus.update') }}" class="mt-6 space-y-6" data-menu-manager data-menu-reorder-url="{{ route('dashboard.menus.reorder') }}">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="cf-stat-card space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Menu items') }}</p>
                    <p class="text-3xl font-bold text-[var(--color-text-primary)]">{{ count($menuItems) }}</p>
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('Header and mobile navigation stay in sync automatically.') }}</p>
                </div>
                <div class="cf-stat-card space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Reordering') }}</p>
                    <p class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Drag and drop') }}</p>
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('Use the handle to move items into the order you want visitors to see first.') }}</p>
                </div>
                <div class="cf-stat-card space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Publishing') }}</p>
                    <p class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('One save updates all') }}</p>
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('The public header and mobile menu both update after a single save.') }}</p>
                </div>
            </div>

            <section class="cf-admin-form-card">
                <div class="cf-admin-section-header">
                    <h2 class="cf-admin-section-title">{{ __('Header Menu Items') }}</h2>
                    <p class="cf-admin-section-copy">{{ __('Drag items using the handle, then update the text and save once you are happy with the final order.') }}</p>
                </div>

                <div data-menu-manager-status class="rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.03)] px-4 py-3 text-sm text-[var(--color-text-muted)] dark:border-white/[0.08] dark:bg-white/[0.03]">
                    {{ __('Ready to reorder menu items.') }}
                </div>

                <div class="space-y-4" data-menu-sorter-list>
                    @foreach ($menuItems as $index => $item)
                        <div class="rounded-[24px] border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.02)] p-5 shadow-[0_14px_32px_rgba(15,23,42,0.04)] transition hover:border-[var(--color-primary)]/20 hover:bg-[var(--color-primary)]/[0.04] dark:border-white/[0.08] dark:bg-white/[0.03]" data-menu-item data-menu-key="{{ $item['key'] }}">
                            <input type="hidden" name="items[{{ $index }}][key]" value="{{ $item['key'] }}">
                            <input type="hidden" name="items[{{ $index }}][is_enabled]" value="{{ $item['is_enabled'] ? 1 : 0 }}">

                            <div class="flex flex-col gap-5 xl:flex-row xl:items-center">
                                <div class="flex items-center gap-4 xl:min-w-[220px]">
                                    <button type="button" draggable="true" class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-[var(--color-primary)]/18 bg-[var(--color-primary)]/10 text-lg text-[var(--color-primary)] shadow-[0_10px_24px_rgba(245,184,0,0.14)] cursor-grab active:cursor-grabbing" title="{{ __('Drag menu item') }}" data-menu-drag-handle>
                                        <span aria-hidden="true">⋮⋮</span>
                                    </button>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[var(--color-text-primary)]" data-menu-order-badge>{{ __('Item :number', ['number' => $index + 1]) }}</p>
                                        <p class="mt-1 inline-flex rounded-full border border-[rgba(15,23,42,0.08)] bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-[var(--color-text-muted)] dark:border-white/[0.08] dark:bg-white/[0.03]">{{ strtoupper($item['key']) }}</p>
                                    </div>
                                </div>

                                <div class="grid flex-1 gap-4 lg:grid-cols-[minmax(0,1.15fr),minmax(0,0.85fr)]">
                                    <div>
                                        <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Menu Text') }}</label>
                                        <input
                                            type="text"
                                            name="items[{{ $index }}][label]"
                                            value="{{ old("items.$index.label", $item['label']) }}"
                                            class="mt-2 block w-full rounded-2xl border border-[rgba(15,23,42,0.08)] bg-white px-4 py-3 text-[var(--color-text-primary)] shadow-none focus:border-[var(--color-primary)] focus:ring-0 dark:border-white/[0.08] dark:bg-[#111111]"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Target') }}</label>
                                        <div class="mt-2 rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.03)] px-4 py-3 text-sm font-medium text-[var(--color-text-primary)] dark:border-white/[0.08] dark:bg-white/[0.03]">
                                            <span class="block text-[11px] font-semibold uppercase tracking-[0.14em] text-[var(--color-text-muted)]">{{ __('Route') }}</span>
                                            <span class="mt-1 block">{{ $item['route_name'] ? __($item['route_name']) : '/' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="cf-button-primary !px-6 !py-3">{{ __('Save Menu') }}</button>
                </div>
            </section>
        </form>
    </div>
</x-app-layout>
