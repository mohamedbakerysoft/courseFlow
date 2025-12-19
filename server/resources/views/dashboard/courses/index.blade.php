<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Courses') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-6xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('dashboard.courses.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded">Create Course</a>
        </div>
        <div class="bg-white shadow rounded">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">Slug</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($courses as $course)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $course->title }}</td>
                        <td class="px-4 py-2">{{ $course->slug }}</td>
                        <td class="px-4 py-2">{{ ucfirst($course->status) }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('dashboard.courses.edit', $course) }}" class="text-blue-600">Edit</a>
                            <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="text-indigo-600">Lessons</a>
                            @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                <form action="{{ route('dashboard.courses.publish', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-green-600">Publish</button>
                                </form>
                            @else
                                <form action="{{ route('dashboard.courses.unpublish', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-yellow-600">Unpublish</button>
                                </form>
                            @endif
                            <form action="{{ route('dashboard.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Delete course?')">
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
