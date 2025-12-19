<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appearance / Branding') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <nav class="mb-4 text-sm">
            <a href="{{ route('dashboard') }}" class="underline text-gray-700">Dashboard</a>
            <span class="text-gray-500">/</span>
            <span class="text-gray-700">Appearance</span>
        </nav>
        <div class="bg-white p-6 rounded shadow">
            <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.appearance.update') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Primary Color</label>
                    <input type="color" name="primary" value="{{ $primary }}" class="h-10 w-16 border rounded">
                    <p class="text-xs text-gray-500 mt-1">Used for main actions and highlights.</p>
                    @error('primary')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Secondary Color</label>
                    <input type="color" name="secondary" value="{{ $secondary }}" class="h-10 w-16 border rounded">
                    <p class="text-xs text-gray-500 mt-1">Used for links and secondary actions.</p>
                    @error('secondary')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Accent Color</label>
                    <input type="color" name="accent" value="{{ $accent }}" class="h-10 w-16 border rounded">
                    <p class="text-xs text-gray-500 mt-1">Used for status badges and success.</p>
                    @error('accent')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <button type="submit" :disabled="isSubmitting" class="bg-[var(--color-primary)] text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
 </x-app-layout>
