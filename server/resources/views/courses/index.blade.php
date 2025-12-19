<x-public-layout :title="'Browse Courses'" :metaDescription="'Browse published courses'">
    <section class="space-y-8">
        <div class="flex items-center justify-between">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900">Browse Courses</h1>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($courses as $course)
                <div class="group relative bg-white rounded-xl shadow-lg ring-1 ring-gray-100 overflow-hidden transition transform hover:-translate-y-1 hover:shadow-2xl">
                    <a href="{{ route('courses.show', $course) }}" class="block">
                        <div class="relative h-44">
                            @if ($course->thumbnail_path)
                                <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-500 text-sm">No image</div>
                            @endif
                            <span class="absolute top-3 left-3 inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                {{ ($course->is_free || (float)$course->price == 0.0) ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ($course->is_free || (float)$course->price == 0.0) ? 'Free' : 'Paid' }}
                            </span>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-lg text-gray-900">{{ $course->title }}</h3>
                            @if (!empty($course->description))
                                <p class="text-gray-600 mt-2">{{ str($course->description)->limit(120) }}</p>
                            @endif
                            <div class="mt-5 flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-900">
                                    @if ($course->is_free || (float)$course->price == 0.0)
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Free</span>
                                    @else
                                        <span>{{ number_format((float)$course->price, 2) }} {{ $course->currency }}</span>
                                    @endif
                                </p>
                                <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-4 py-2 rounded bg-[var(--color-primary)] text-white text-sm hover:opacity-90">
                                    View Course
                                </a>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 p-10 text-center">
                    <div class="text-5xl mb-4">🎓</div>
                    <p class="text-gray-800 font-semibold text-lg">No courses yet</p>
                    <p class="text-gray-500 text-sm mt-1">Please check back soon.</p>
                    <div class="mt-6">
                        <a href="{{ url('/') }}" class="inline-flex items-center px-5 py-2.5 rounded bg-[var(--color-primary)] text-white">Go to homepage</a>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $courses->links() }}
        </div>
    </section>
</x-public-layout>
