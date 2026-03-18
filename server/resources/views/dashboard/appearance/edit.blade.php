<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Appearance / Branding') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="mb-4 text-sm">
            <a href="{{ route('dashboard') }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">Dashboard</a>
            <span class="text-[var(--color-text-muted)]">/</span>
            <span class="text-[var(--color-text-muted)]">Appearance</span>
        </nav>
        <div class="cf-admin-form-card p-6 shadow-none">
            <form x-data="{isSubmitting:false, tab: 'colors'}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.appearance.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="flex gap-3 border-b border-[var(--color-secondary)]/10 pb-2">
                    <button type="button" x-on:click="tab='colors'" :class="tab==='colors' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Colors</button>
                    <button type="button" x-on:click="tab='typography'" :class="tab==='typography' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Typography</button>
                    <button type="button" x-on:click="tab='layout'" :class="tab==='layout' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Layout</button>
                </div>
                <div x-show="tab==='colors'" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Primary Color</label>
                        <input type="color" name="primary" value="{{ $primary }}" class="h-10 w-16 rounded-xl border border-[var(--color-secondary)]/20 bg-white">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Used for main actions, active controls, and highlighted CTAs.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Secondary Color</label>
                        <input type="color" name="secondary" value="{{ $secondary }}" class="h-10 w-16 rounded-xl border border-[var(--color-secondary)]/20 bg-white">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Used for dark shells, heading color, and strong contrast surfaces.</p>
                        @error('secondary')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Accent Color</label>
                        <input type="color" name="accent" value="{{ $accent }}" class="h-10 w-16 rounded-xl border border-[var(--color-secondary)]/20 bg-white">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Used for soft surfaces, chips, and supporting backgrounds.</p>
                        @error('accent')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div x-show="tab==='typography'" class="space-y-6">
                    <div>
                        <label for="english_font" class="block text-sm font-medium mb-1">English Font</label>
                        <select id="english_font" name="english_font" class="mt-1 block w-64 rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            <option value="Manrope" @selected($englishFont==='Manrope')>Manrope</option>
                        </select>
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Applied across the entire website and admin panel. Manrope is enforced globally for one consistent premium font.</p>
                        @error('english_font')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div x-show="tab==='layout'" class="space-y-6">
                    <div>
                        <span class="block text-sm font-medium text-[var(--color-text-muted)] mb-2">Landing Layout</span>
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
