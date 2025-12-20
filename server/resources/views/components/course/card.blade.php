@props(['course', 'ctaLabel' => 'View course', 'ctaUrl' => null])
@php($ctaUrl = $ctaUrl ?? route('courses.show', $course))
<article class="group relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 overflow-hidden transition transform hover:-translate-y-1 hover:shadow-md">
    <a href="{{ $ctaUrl }}" class="block h-full">
        <div class="relative aspect-video overflow-hidden">
            @if ($course->thumbnail_path)
                <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-gradient-to-br from-[var(--color-primary)]/10 via-white to-[var(--color-primary)]/5 flex items-center justify-center text-gray-500 text-sm">
                    {{ __('Course preview') }}
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/5 to-transparent opacity-60 group-hover:opacity-75 transition"></div>
            <div class="absolute top-3 start-3 flex items-center gap-2">
                @if ($course->is_free || (float)$course->price == 0.0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-[var(--color-primary)] text-white text-xs font-semibold">
                        {{ __('Free') }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-[var(--color-primary)] text-white text-xs font-semibold">
                        {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                    </span>
                @endif
            </div>
        </div>
        <div class="p-4 flex flex-col h-full space-y-3">
            <h3 class="font-semibold text-base text-gray-900 line-clamp-2">
                {{ $course->title }}
            </h3>
            @if (!empty($course->description))
                <p class="text-sm text-gray-600 line-clamp-2">
                    {{ str($course->description)->limit(120) }}
                </p>
            @endif
            <div class="flex items-center justify-between text-xs text-gray-500">
                <div class="flex flex-col">
                    @if ($course->instructor)
                        <span class="font-medium text-gray-800">
                            {{ $course->instructor->name }}
                        </span>
                    @else
                        <span class="font-medium text-gray-800">
                            {{ __('Instructor') }}
                        </span>
                    @endif
                    <div class="flex items-center gap-2 text-gray-500 mt-1">
                        @if (isset($course->lessons_count))
                            <span>
                                {{ $course->lessons_count }} {{ Str::plural(__('lesson'), $course->lessons_count) }}
                            </span>
                        @endif
                        <span>
                            {{ strtoupper($course->language ?? 'en') }}
                        </span>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] px-3 py-1 font-medium">
                    {{ $ctaLabel }}
                </span>
            </div>
        </div>
    </a>
</article>
