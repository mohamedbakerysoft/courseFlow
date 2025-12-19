<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Courses') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-6xl mx-auto">
        <nav class="mb-4 text-sm">
            <a href="{{ route('dashboard') }}" class="underline text-gray-700">Dashboard</a>
            <span class="text-gray-500">/</span>
            <span class="text-gray-700">Courses</span>
        </nav>
        <div class="mb-4">
            <a href="{{ route('dashboard.courses.create') }}" class="inline-block bg-[var(--color-primary)] text-white px-4 py-2 rounded">Create Course</a>
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
                @forelse($courses as $course)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $course->title }}</td>
                        <td class="px-4 py-2">{{ $course->slug }}</td>
                        <td class="px-4 py-2">
                            @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs">Draft</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Published</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('dashboard.courses.edit', $course) }}" class="text-[var(--color-secondary)]">Edit</a>
                            <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="text-[var(--color-secondary)]">Lessons</a>
                            @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.publish', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button :disabled="isSubmitting" class="text-[var(--color-accent)]">Publish</button>
                                </form>
                            @else
                                <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.unpublish', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button :disabled="isSubmitting" class="text-gray-600">Unpublish</button>
                                </form>
                            @endif
                            <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Delete course?')">
                                @csrf
                                @method('DELETE')
                                <button :disabled="isSubmitting" class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-700">
                            <div class="text-3xl mb-2">📚</div>
                            You have not created any courses yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
