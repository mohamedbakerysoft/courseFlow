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
            <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.appearance.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="cf-admin-form-grid">
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
                <div>
                    <button type="submit" :disabled="isSubmitting" class="cf-button-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
 </x-app-layout>
