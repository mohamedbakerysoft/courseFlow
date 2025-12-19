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
                    Free
                @else
                    {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                @endif
            </p>
        </div>
    </a>
</article>

