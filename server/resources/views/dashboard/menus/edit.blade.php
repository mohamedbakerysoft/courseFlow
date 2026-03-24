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

            <section class="cf-admin-form-card">
                <div class="cf-admin-section-header">
                    <h2 class="cf-admin-section-title">{{ __('Header Menu Items') }}</h2>
                    <p class="cf-admin-section-copy">{{ __('Drag items using the handle, then update the text and save once you are happy with the final order.') }}</p>
                </div>

                <div data-menu-manager-status class="text-sm text-[var(--color-text-muted)]">{{ __('Ready to reorder menu items.') }}</div>

                <div class="space-y-4" data-menu-sorter-list>
                    @foreach ($menuItems as $index => $item)
                        <div class="rounded-[5px] border border-[rgba(11,11,11,0.08)] bg-white p-4 shadow-[0_10px_24px_rgba(11,11,11,0.03)]" data-menu-item data-menu-key="{{ $item['key'] }}">
                            <input type="hidden" name="items[{{ $index }}][key]" value="{{ $item['key'] }}">
                            <input type="hidden" name="items[{{ $index }}][is_enabled]" value="{{ $item['is_enabled'] ? 1 : 0 }}">

                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center">
                                <div class="flex items-center gap-3">
                                    <button type="button" draggable="true" class="inline-flex h-11 w-11 items-center justify-center rounded-[5px] border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 text-lg text-[var(--color-text-muted)] cursor-grab active:cursor-grabbing" title="{{ __('Drag menu item') }}" data-menu-drag-handle>
                                        <span aria-hidden="true">⋮⋮</span>
                                    </button>
                                    <div>
                                        <p class="text-sm font-semibold text-[var(--color-text-primary)]" data-menu-order-badge>{{ __('Item :number', ['number' => $index + 1]) }}</p>
                                        <p class="text-xs text-[var(--color-text-muted)]">{{ strtoupper($item['key']) }}</p>
                                    </div>
                                </div>

                                <div class="grid flex-1 gap-4 md:grid-cols-[minmax(0,1fr),minmax(0,1fr)]">
                                    <div>
                                        <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Menu Text') }}</label>
                                        <input
                                            type="text"
                                            name="items[{{ $index }}][label]"
                                            value="{{ old("items.$index.label", $item['label']) }}"
                                            class="mt-1 block w-full rounded-[5px] border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Target') }}</label>
                                        <div class="mt-1 rounded-[5px] border border-[rgba(11,11,11,0.08)] bg-[rgba(247,247,247,0.9)] px-4 py-3 text-sm text-[var(--color-text-primary)]">
                                            {{ $item['route_name'] ? __($item['route_name']) : '/' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="cf-button-primary">{{ __('Save Menu') }}</button>
                </div>
            </section>
        </form>
    </div>
</x-app-layout>
