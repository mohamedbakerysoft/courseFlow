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
            <form x-data="{isSubmitting:false, tab: 'colors'}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.appearance.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="flex gap-3 border-b pb-2">
                    <button type="button" x-on:click="tab='colors'" :class="tab==='colors' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Colors</button>
                    <button type="button" x-on:click="tab='typography'" :class="tab==='typography' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Typography</button>
                    <button type="button" x-on:click="tab='hero'" :class="tab==='hero' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Hero</button>
                    <button type="button" x-on:click="tab='layout'" :class="tab==='layout' ? 'text-[var(--color-primary)] font-semibold' : 'text-[var(--color-text-muted)]'" class="text-sm">Layout</button>
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
                <div x-show="tab==='hero'" class="space-y-6">
                    <div>
                        <label for="hero_image" class="block text-sm font-medium mb-1">Hero Image</label>
                        <input id="hero_image" name="hero_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[var(--color-text-primary)] border-[var(--color-secondary)]/30 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @if ($heroImageUrl)
                            <div class="mt-3">
                                <p class="text-xs text-[var(--color-text-muted)] mb-1">Current Image</p>
                                <div class="rounded-xl ring-1 ring-[var(--color-secondary)]/20 shadow-sm overflow-hidden w-64">
                                    <img src="{{ $heroImageUrl }}" alt="Hero" class="w-64 h-40 object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-sm font-medium text-[var(--color-text-muted)]">Image Fit</span>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                    <input type="radio" name="hero_image_fit" value="contain" {{ ($heroImageFit ?? 'contain') === 'contain' ? 'checked' : '' }}>
                                    <span class="text-sm text-[var(--color-text-primary)]">Fit (Show Full Image)</span>
                                </label>
                                <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                    <input type="radio" name="hero_image_fit" value="cover" {{ ($heroImageFit ?? 'contain') === 'cover' ? 'checked' : '' }}>
                                    <span class="text-sm text-[var(--color-text-primary)]">Fill (Crop to Container)</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="hero_image_focus" class="block text-sm font-medium text-[var(--color-text-muted)]">Image Focus</label>
                            @php $focus = $heroImageFocus ?? 'center'; @endphp
                            <select id="hero_image_focus" name="hero_image_focus" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                <option value="center" @selected($focus === 'center')>Center</option>
                                <option value="top" @selected($focus === 'top')>Top</option>
                                <option value="bottom" @selected($focus === 'bottom')>Bottom</option>
                                <option value="left" @selected($focus === 'left')>Left</option>
                                <option value="right" @selected($focus === 'right')>Right</option>
                            </select>
                        </div>
                        <div>
                            <label for="hero_image_ratio" class="block text-sm font-medium text-[var(--color-text-muted)]">Image Ratio</label>
                            @php $ratio = $heroImageRatio ?? '16:9'; @endphp
                            <select id="hero_image_ratio" name="hero_image_ratio" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                <option value="16:9" @selected($ratio === '16:9')>16:9</option>
                                <option value="4:5" @selected($ratio === '4:5')>4:5</option>
                                <option value="1:1" @selected($ratio === '1:1')>1:1</option>
                            </select>
                            <p class="text-[11px] text-[var(--color-text-muted)] mt-1">Uses CSS object-fit and aspect-ratio for safe rendering.</p>
                        </div>
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
                    <button type="submit" :disabled="isSubmitting" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
 </x-app-layout>
