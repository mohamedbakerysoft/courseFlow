@props(['course'])
<article class="bg-white rounded shadow overflow-hidden">
    <a href="{{ route('courses.show', $course) }}" class="block">
        @if ($course->thumbnail_path)
            <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-40 object-cover">
        @endif
        <div class="p-4">
            <h3 class="font-semibold text-lg">{{ $course->title }}</h3>
            @if (!empty($course->description))
                <p class="text-gray-600 mt-1">{{ str($course->description)->limit(140) }}</p>
            @endif
            <p class="mt-3 font-medium">
                @if ($course->is_free || (float)$course->price == 0.0)
                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700 text-sm">Free</span>
                @else
                    <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-700 text-sm">
                        {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                    </span>
                @endif
            </p>
        </div>
    </a>
</article>
