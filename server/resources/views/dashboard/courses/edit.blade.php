<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <nav class="mb-4 text-sm">
            <a href="{{ route('dashboard') }}" class="underline text-gray-700">Dashboard</a>
            <span class="text-gray-500">/</span>
            <a href="{{ route('dashboard.courses.index') }}" class="underline text-gray-700">Courses</a>
            <span class="text-gray-500">/</span>
            <span class="text-gray-700">Edit</span>
        </nav>
        <div class="mb-4">
            <a href="{{ route('dashboard.courses.index') }}" class="text-blue-600">Back to Courses</a>
        </div>
        <div class="mb-4 text-sm text-gray-600">
            Status: <span class="font-medium">{{ ucfirst($course->status) }}</span>
        </div>
        <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" method="POST" action="{{ route('dashboard.courses.update', $course) }}" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input name="title" type="text" class="mt-1 w-full border rounded p-2" value="{{ $course->title }}" required>
                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" type="text" class="mt-1 w-full border rounded p-2" value="{{ $course->slug }}" required>
                <p class="text-xs text-gray-500 mt-1">Only letters, numbers, and dashes.</p>
                @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="mt-1 w-full border rounded p-2">{{ $course->description }}</textarea>
                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Thumbnail Path</label>
                    <input name="thumbnail_path" type="text" class="mt-1 w-full border rounded p-2" value="{{ $course->thumbnail_path }}">
                    <p class="text-xs text-gray-500 mt-1">Public path to image file.</p>
                    @error('thumbnail_path')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Language</label>
                    <input name="language" type="text" class="mt-1 w-full border rounded p-2" value="{{ $course->language }}">
                    @error('language')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Price</label>
                    <input name="price" type="number" step="0.01" class="mt-1 w-full border rounded p-2" value="{{ $course->price }}">
                    <p class="text-xs text-gray-500 mt-1">Set 0 for free courses.</p>
                    @error('price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Currency</label>
                    <input name="currency" type="text" class="mt-1 w-full border rounded p-2" value="{{ $course->currency }}">
                    @error('currency')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center">
                    <input id="is_free" name="is_free" type="checkbox" value="1" class="mr-2" {{ $course->is_free ? 'checked' : '' }}>
                    <label for="is_free" class="text-sm">Is Free</label>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" :disabled="isSubmitting" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
        <div class="mt-4 flex items-center space-x-4">
            @if($course->status === \App\Models\Course::STATUS_DRAFT)
            <form action="{{ route('dashboard.courses.publish', $course) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Publish</button>
            </form>
            @else
            <form action="{{ route('dashboard.courses.unpublish', $course) }}" method="POST">
                @csrf
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Unpublish</button>
            </form>
            @endif
        </div>
        <div class="mt-6">
            <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded">Manage Lessons</a>
        </div>
    </div>
</x-app-layout>
