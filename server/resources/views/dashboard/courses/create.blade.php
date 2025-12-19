<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Course') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <form method="POST" action="{{ route('dashboard.courses.store') }}" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input name="title" type="text" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" type="text" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="mt-1 w-full border rounded p-2"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Thumbnail Path</label>
                    <input name="thumbnail_path" type="text" class="mt-1 w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Language</label>
                    <input name="language" type="text" value="en" class="mt-1 w-full border rounded p-2">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Price</label>
                    <input name="price" type="number" step="0.01" value="0" class="mt-1 w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Currency</label>
                    <input name="currency" type="text" value="USD" class="mt-1 w-full border rounded p-2">
                </div>
                <div class="flex items-center">
                    <input id="is_free" name="is_free" type="checkbox" value="1" class="mr-2" checked>
                    <label for="is_free" class="text-sm">Is Free</label>
                </div>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
