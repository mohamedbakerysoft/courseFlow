<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Brand system') }}</p>
            <h2 class="cf-dark-title text-3xl font-bold tracking-[-0.04em] sm:text-4xl">{{ __('Appearance / Branding') }}</h2>
            <p class="cf-dark-copy max-w-2xl text-sm leading-7">{{ __('Keep the storefront and admin panel visually aligned with one fixed brand font and color system.') }}</p>
        </div>
    </x-slot>

    <div class="cf-admin-shell-narrow">
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Appearance')]
        ]" />

        <div class="cf-admin-form-card !p-6 sm:!p-7 shadow-none">
            <form x-data="{isSubmitting:false, tab: 'colors'}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.appearance.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="cf-admin-tabbar">
                    <button type="button" x-on:click="tab='colors'" :class="tab==='colors' ? 'cf-admin-tab is-active' : 'cf-admin-tab'">Colors</button>
                    <button type="button" x-on:click="tab='layout'" :class="tab==='layout' ? 'cf-admin-tab is-active' : 'cf-admin-tab'">Layout</button>
                </div>
                <div x-show="tab==='colors'" class="cf-admin-form-grid">
                    <div class="cf-admin-field">
                        <label>Primary Color</label>
                        <input type="color" name="primary" value="{{ $primary }}" class="h-10 w-16 rounded-xl border border-[var(--color-secondary)]/20 bg-white">
                        <p class="cf-admin-helper">Used for main actions, active controls, and highlighted CTAs.</p>
                    </div>
                    <div class="cf-admin-field">
                        <label>Secondary Color</label>
                        <input type="color" name="secondary" value="{{ $secondary }}" class="h-10 w-16 rounded-xl border border-[var(--color-secondary)]/20 bg-white">
                        <p class="cf-admin-helper">Used for dark shells, heading color, and strong contrast surfaces.</p>
                        @error('secondary')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="cf-admin-field">
                        <label>Accent Color</label>
                        <input type="color" name="accent" value="{{ $accent }}" class="h-10 w-16 rounded-xl border border-[var(--color-secondary)]/20 bg-white">
                        <p class="cf-admin-helper">Used for soft surfaces, chips, and supporting backgrounds.</p>
                        @error('accent')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div x-show="tab==='layout'" class="cf-admin-form-grid">
                    <div class="cf-admin-field">
                        <span>Landing Layout</span>
                        <p class="cf-admin-helper mb-4">The Learnova storefront now uses one fixed brand font across the public site and admin panel for a fully consistent look.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <label class="block rounded-lg border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_layout" value="default" class="mb-2" {{ ($landingLayout ?? 'default') === 'default' ? 'checked' : '' }}>
                                <div class="rounded-md overflow-hidden ring-1 ring-[var(--color-secondary)]/10 bg-white">
                                    <img src="{{ asset('images/layouts/default.svg') }}" alt="Default Layout" class="w-full h-28 object-contain">
                                </div>
                                <p class="mt-2 text-sm font-medium text-[var(--color-text-primary)]">Default</p>
                                <p class="text-xs text-[var(--color-text-muted)]">Balanced hero + courses.</p>
                            </label>
                            <label class="block rounded-lg border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_layout" value="layout_v2" class="mb-2" {{ ($landingLayout ?? 'default') === 'layout_v2' ? 'checked' : '' }}>
                                <div class="rounded-md overflow-hidden ring-1 ring-[var(--color-secondary)]/10 bg-white">
                                    <img src="{{ asset('images/layouts/v2.svg') }}" alt="Layout v2" class="w-full h-28 object-contain">
                                </div>
                                <p class="mt-2 text-sm font-medium text-[var(--color-text-primary)]">Modern Alt</p>
                                <p class="text-xs text-[var(--color-text-muted)]">Image-forward hero.</p>
                            </label>
                            <label class="block rounded-lg border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_layout" value="layout_v3" class="mb-2" {{ ($landingLayout ?? 'default') === 'layout_v3' ? 'checked' : '' }}>
                                <div class="rounded-md overflow-hidden ring-1 ring-[var(--color-secondary)]/10 bg-white">
                                    <img src="{{ asset('images/layouts/v3.svg') }}" alt="Layout v3" class="w-full h-28 object-contain">
                                </div>
                                <p class="mt-2 text-sm font-medium text-[var(--color-text-primary)]">Minimal / Bold</p>
                                <p class="text-xs text-[var(--color-text-muted)]">Big title, clean sections.</p>
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" :disabled="isSubmitting" class="cf-button-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
 </x-app-layout>
