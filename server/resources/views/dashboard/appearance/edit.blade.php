<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Appearance / Branding') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <nav class="mb-4 text-sm">
            <a href="{{ route('dashboard') }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">Dashboard</a>
            <span class="text-[var(--color-text-muted)]">/</span>
            <span class="text-[var(--color-text-muted)]">Appearance</span>
        </nav>
        <div class="bg-white p-6 rounded shadow">
            <form x-data="{isSubmitting:false, tab: 'colors'}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.appearance.update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="flex gap-3 border-b pb-2">
                    <button type="button" x-on:click="tab='colors'" :class="tab==='colors' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Colors</button>
                    <button type="button" x-on:click="tab='typography'" :class="tab==='typography' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Typography</button>
                </div>
                <div x-show="tab==='colors'" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Primary Color</label>
                        <input type="color" name="primary" value="{{ $primary }}" class="h-10 w-16 border rounded">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Used for main actions and highlights.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Secondary Color</label>
                        <input type="color" name="secondary" value="{{ $secondary }}" class="h-10 w-16 border rounded">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Used for links and secondary actions.</p>
                        @error('secondary')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Accent Color</label>
                        <input type="color" name="accent" value="{{ $accent }}" class="h-10 w-16 border rounded">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Used for status badges and success.</p>
                        @error('accent')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div x-show="tab==='typography'" class="space-y-6">
                    <div>
                        <label for="arabic_font" class="block text-sm font-medium mb-1">Arabic Font</label>
                        <select id="arabic_font" name="arabic_font" class="mt-1 block w-64 rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            <option value="Cairo" @selected($arabicFont==='Cairo')>Cairo</option>
                            <option value="Tajawal" @selected($arabicFont==='Tajawal')>Tajawal</option>
                            <option value="IBM Plex Arabic" @selected($arabicFont==='IBM Plex Arabic')>IBM Plex Arabic</option>
                        </select>
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Applied to Arabic pages (lang="ar").</p>
                        @error('arabic_font')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="english_font" class="block text-sm font-medium mb-1">English Font</label>
                        <select id="english_font" name="english_font" class="mt-1 block w-64 rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            <option value="Inter" @selected($englishFont==='Inter')>Inter</option>
                            <option value="Poppins" @selected($englishFont==='Poppins')>Poppins</option>
                            <option value="Roboto" @selected($englishFont==='Roboto')>Roboto</option>
                        </select>
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">Applied to non-Arabic pages.</p>
                        @error('english_font')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <button type="submit" :disabled="isSubmitting" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
 </x-app-layout>
