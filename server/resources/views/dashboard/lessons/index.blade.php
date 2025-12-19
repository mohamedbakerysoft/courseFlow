<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lessons for') }} {{ $course->title }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-6xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('dashboard.courses.lessons.create', $course) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded">Add Lesson</a>
            <a href="{{ route('dashboard.courses.edit', $course) }}" class="inline-block ml-2 text-blue-600">Back to Course</a>
        </div>
        <div class="bg-white shadow rounded">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">Slug</th>
                        <th class="px-4 py-2 text-left">Position</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($lessons as $lesson)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $lesson->title }}</td>
                        <td class="px-4 py-2">{{ $lesson->slug }}</td>
                        <td class="px-4 py-2">{{ $lesson->position }}</td>
                        <td class="px-4 py-2">{{ ucfirst($lesson->status) }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('dashboard.lessons.edit', $lesson) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('dashboard.lessons.destroy', $lesson) }}" method="POST" class="inline" onsubmit="return confirm('Delete lesson?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
