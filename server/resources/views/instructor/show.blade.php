<x-public-layout :title="'Instructor'" :metaDescription="$instructor->bio ?? ''">
    <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1 space-y-4">
            @if ($instructor->profile_image_path)
                <img src="{{ asset($instructor->profile_image_path) }}" alt="{{ $instructor->name }}" class="w-48 h-48 rounded-full object-cover">
            @endif
            <h1 class="text-2xl font-semibold">{{ $instructor->name }}</h1>
            @if ($instructor->bio)
                <p class="text-gray-700">{{ $instructor->bio }}</p>
            @endif
            @if (!empty($links))
                <div class="flex gap-3">
                    @foreach ($links as $label => $url)
                        <a href="{{ $url }}" class="text-blue-600 hover:underline" rel="noopener" target="_blank">{{ ucfirst($label) }}</a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="md:col-span-2">
            <h2 class="text-xl font-semibold mb-4">Published Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($courses as $course)
                    <article class="bg-white rounded shadow p-4">
                        @if ($course->thumbnail_path)
                            <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-40 object-cover rounded">
                        @endif
                        <h3 class="mt-3 font-medium">{{ $course->title }}</h3>
                    </article>
                @empty
                    <p class="text-gray-600">No published courses yet.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-public-layout>
