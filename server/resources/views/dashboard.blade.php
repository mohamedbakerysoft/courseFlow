<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('viewAny', \App\Models\Course::class)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="{{ route('dashboard.courses.index') }}" class="block bg-white border border-gray-200 rounded p-6 hover:shadow">
                        <div class="text-lg font-semibold mb-2">Manage Courses</div>
                        <p class="text-sm text-gray-600">Create, edit, publish courses.</p>
                    </a>
                    <a href="{{ route('dashboard.courses.index') }}" class="block bg-white border border-gray-200 rounded p-6 hover:shadow">
                        <div class="text-lg font-semibold mb-2">Manage Lessons</div>
                        <p class="text-sm text-gray-600">Add and organize lessons per course.</p>
                    </a>
                    <a href="{{ route('dashboard.appearance.edit') }}" class="block bg-white border border-gray-200 rounded p-6 hover:shadow">
                        <div class="text-lg font-semibold mb-2">Site Appearance</div>
                        <p class="text-sm text-gray-600">Update theme colors and branding.</p>
                    </a>
                    <a href="{{ url('/') }}" class="block bg-white border border-gray-200 rounded p-6 hover:shadow">
                        <div class="text-lg font-semibold mb-2">View Site</div>
                        <p class="text-sm text-gray-600">Open the public site.</p>
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">My Courses</h3>
                        @if (!empty($enrolledCourses) && $enrolledCourses->count())
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($enrolledCourses as $course)
                                    <x-course.card :course="$course" ctaLabel="Continue" />
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-700">
                                <div class="text-4xl mb-3">🎓</div>
                                <p class="font-medium">No enrollments yet</p>
                                <p class="text-sm text-gray-500 mt-1">Browse courses to get started.</p>
                                <div class="mt-4">
                                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 rounded bg-[var(--color-primary)] text-white">Browse Courses</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
