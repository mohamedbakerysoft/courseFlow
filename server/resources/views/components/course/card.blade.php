@props(['course', 'ctaLabel' => 'View', 'ctaUrl' => null])
@php($ctaUrl = $ctaUrl ?? route('courses.show', $course))
<article class="bg-white rounded-lg border border-gray-200 overflow-hidden transition transform hover:shadow-lg hover:-translate-y-0.5">
    <a href="{{ route('courses.show', $course) }}" class="block">
        @if ($course->thumbnail_path)
            <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-44 object-cover">
        @else
            <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-500 text-sm">No image</div>
        @endif
        <div class="p-4">
            <h3 class="font-semibold text-lg text-gray-900">{{ $course->title }}</h3>
            @if (!empty($course->description))
                <p class="text-gray-600 mt-2">{{ str($course->description)->limit(120) }}</p>
            @endif
            <div class="mt-4 flex items-center justify-between">
                <p class="font-medium">
                    @if ($course->is_free || (float)$course->price == 0.0)
                        <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-700 text-xs">Free</span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs">
                            {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                        </span>
                    @endif
                </p>
                <span class="inline-flex items-center">
                    <a href="{{ $ctaUrl }}" class="inline-flex items-center px-3 py-2 rounded bg-[var(--color-primary)] text-white text-sm hover:opacity-90">
                        {{ $ctaLabel }}
                    </a>
                </span>
            </div>
        </div>
    </a>
</article>
