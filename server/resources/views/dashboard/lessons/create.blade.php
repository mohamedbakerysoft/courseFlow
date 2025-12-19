<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Lesson') }} — {{ $course->title }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <form method="POST" action="{{ route('dashboard.courses.lessons.store', $course) }}" class="space-y-4 bg-white p-6 rounded shadow">
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
                <label class="block text-sm font-medium">Video URL</label>
                <input name="video_url" type="url" class="mt-1 w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Position</label>
                <input name="position" type="number" class="mt-1 w-full border rounded p-2" value="1" required>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
