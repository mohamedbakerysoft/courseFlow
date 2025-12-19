<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ __('Lessons for') }} {{ $course->title }}
            </h1>
            @isset($course)
                <a href="{{ route('dashboard.courses.lessons.create', $course) }}" class="inline-flex items-center px-4 py-2 rounded border text-gray-700 hover:bg-gray-50">
                    {{ __('Add Lesson') }}
                </a>
            @endisset
        </div>
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Courses'), 'url' => route('dashboard.courses.index')],
            ['label' => __('Lessons')]
        ]" />
        <div class="mb-4 flex items-center justify-end">
            <a href="{{ route('dashboard.courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 rounded border text-gray-700 hover:bg-gray-50">
                {{ __('Back to Course') }}
            </a>
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
                @forelse($lessons as $lesson)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $lesson->title }}</td>
                        <td class="px-4 py-2">{{ $lesson->slug }}</td>
                        <td class="px-4 py-2">{{ $lesson->position }}</td>
                        <td class="px-4 py-2">
                            @if($lesson->status === \App\Models\Lesson::STATUS_DRAFT)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs">Draft</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Published</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('dashboard.lessons.edit', $lesson) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('dashboard.lessons.destroy', $lesson) }}" method="POST" class="inline" onsubmit="return confirm('Delete lesson?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-700">
                            <div class="text-3xl mb-2">🗒️</div>
                            No lessons added yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
