<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Lesson') }} — {{ $lesson->course->title }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('dashboard.courses.lessons.index', $lesson->course) }}" class="text-blue-600">Back to Lessons</a>
        </div>
        <form method="POST" action="{{ route('dashboard.lessons.update', $lesson) }}" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input name="title" type="text" class="mt-1 w-full border rounded p-2" value="{{ $lesson->title }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" type="text" class="mt-1 w-full border rounded p-2" value="{{ $lesson->slug }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Video URL</label>
                <input name="video_url" type="url" class="mt-1 w-full border rounded p-2" value="{{ $lesson->video_url }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Position</label>
                <input name="position" type="number" class="mt-1 w-full border rounded p-2" value="{{ $lesson->position }}" required>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
