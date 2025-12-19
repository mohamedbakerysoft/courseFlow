<x-public-layout :title="$lesson->title" :metaDescription="str($lesson->description)->limit(160)">
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => $course->title, 'url' => route('courses.show', $course)],
        ['label' => $lesson->title]
    ]" />
    <article class="max-w-3xl">
        <h1 class="text-2xl font-semibold mb-6">{{ $lesson->title }}</h1>
        @if ($isCompleted)
            <span class="inline-block px-3 py-1 rounded bg-green-600 text-white mb-4">Completed</span>
        @endif
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
        <p class="text-sm text-gray-600 mb-6">Progress: {{ $progressPercent }}%</p>
        @if (!empty($lesson->description))
            <div class="prose max-w-none">
                {!! nl2br(e($lesson->description)) !!}
            </div>
        @endif
        <div class="mt-8 flex items-center space-x-3">
            @if (!empty($prevLesson))
                <a href="{{ route('lessons.show', [$course, $prevLesson]) }}" class="inline-flex items-center px-4 py-2 rounded border text-gray-700 hover:bg-gray-50">Previous</a>
            @endif
            @if (!empty($nextLesson))
                <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="inline-flex items-center px-4 py-2 rounded bg-[var(--color-primary)] text-white hover:opacity-90">Next</a>
            @endif
            <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-4 py-2 rounded border text-gray-700 hover:bg-gray-50">Back to Course</a>
        </div>
    </article>
</x-public-layout>
