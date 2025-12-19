@props(['course', 'ctaLabel' => 'View course', 'ctaUrl' => null])
@php($ctaUrl = $ctaUrl ?? route('courses.show', $course))
<article class="group relative bg-white rounded-2xl shadow-md ring-1 ring-gray-100 overflow-hidden transition transform hover:-translate-y-1 hover:shadow-xl">
    <a href="{{ $ctaUrl }}" class="block h-full">
        <div class="relative h-44 overflow-hidden">
            @if ($course->thumbnail_path)
                <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-gradient-to-br from-[var(--color-primary)]/10 via-[var(--color-secondary)]/10 to-[var(--color-accent)]/10 flex items-center justify-center text-gray-500 text-sm">
                    Course preview
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/5 to-transparent opacity-70 group-hover:opacity-80 transition"></div>
            <div class="absolute top-3 left-3 flex items-center gap-2">
                @if ($course->is_free || (float)$course->price == 0.0)
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                        Free
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-white/90 text-gray-900 text-xs font-semibold">
                        {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                    </span>
                @endif
            </div>
        </div>
        <div class="p-4 flex flex-col h-full">
            <h3 class="font-semibold text-base text-gray-900 line-clamp-2">
                {{ $course->title }}
            </h3>
            @if (!empty($course->description))
                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                    {{ str($course->description)->limit(120) }}
                </p>
            @endif
            <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                <div class="flex flex-col">
                    @if ($course->instructor)
                        <span class="font-medium text-gray-800">
                            {{ $course->instructor->name }}
                        </span>
                    @else
                        <span class="font-medium text-gray-800">
                            Instructor
                        </span>
                    @endif
                    <span class="text-gray-500">
                        {{ strtoupper($course->language ?? 'en') }}
                    </span>
                </div>
                <span class="inline-flex items-center rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] px-3 py-1 font-medium">
                    {{ $ctaLabel }}
                </span>
            </div>
        </div>
    </a>
</article>
