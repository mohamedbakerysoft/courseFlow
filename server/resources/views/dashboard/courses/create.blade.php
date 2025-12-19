<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Course') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" method="POST" action="{{ route('dashboard.courses.store') }}" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input name="title" type="text" class="mt-1 w-full border rounded p-2" required>
                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" type="text" class="mt-1 w-full border rounded p-2" required>
                <p class="text-xs text-gray-500 mt-1">Only letters, numbers, and dashes.</p>
                @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="mt-1 w-full border rounded p-2"></textarea>
                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Thumbnail Path</label>
                    <input name="thumbnail_path" type="text" class="mt-1 w-full border rounded p-2">
                    <p class="text-xs text-gray-500 mt-1">Public path to image file.</p>
                    @error('thumbnail_path')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Language</label>
                    <input name="language" type="text" value="en" class="mt-1 w-full border rounded p-2">
                    @error('language')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Price</label>
                    <input name="price" type="number" step="0.01" value="0" class="mt-1 w-full border rounded p-2">
                    <p class="text-xs text-gray-500 mt-1">Set 0 for free courses.</p>
                    @error('price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Currency</label>
                    <input name="currency" type="text" value="USD" class="mt-1 w-full border rounded p-2">
                    @error('currency')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center">
                    <input id="is_free" name="is_free" type="checkbox" value="1" class="mr-2" checked>
                    <label for="is_free" class="text-sm">Is Free</label>
                </div>
            </div>
            <div>
                <button type="submit" :disabled="isSubmitting" class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
