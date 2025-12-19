<x-public-layout :title="$lesson->title" :metaDescription="str($lesson->description)->limit(160)">
    <nav class="mb-4 text-sm">
        <a href="{{ route('courses.show', $course) }}" class="underline text-gray-700">{{ $course->title }}</a>
        <span class="text-gray-500">/</span>
        <span class="text-gray-700">{{ $lesson->title }}</span>
    </nav>
    <article class="max-w-3xl">
        <h1 class="text-2xl font-semibold mb-4">{{ $lesson->title }}</h1>
        <div class="aspect-video bg-black mb-6">
            <iframe
                src="{{ $lesson->video_url }}"
                class="w-full h-full"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                referrerpolicy="no-referrer"
            ></iframe>
        </div>
        @if (!empty($lesson->description))
            <div class="prose max-w-none">
                {!! nl2br(e($lesson->description)) !!}
            </div>
        @endif
    </article>
</x-public-layout>

