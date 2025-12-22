<x-public-layout :title="$lesson->title" :metaDescription="str($lesson->description)->limit(160)">
    <x-breadcrumbs :items="[
        ['label' => __('Dashboard'), 'url' => route('dashboard')],
        ['label' => $course->title, 'url' => route('courses.show', $course)],
        ['label' => $lesson->title]
    ]" />
    <article class="max-w-3xl">
        <h1 class="text-2xl font-semibold mb-6">{{ $lesson->title }}</h1>
        @if ($isCompleted)
            <span class="inline-block px-3 py-1 rounded bg-[var(--color-accent)] text-white mb-4">{{ __('Completed') }}</span>
            <div class="mb-4 rounded-lg border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 px-4 py-3 text-sm text-[var(--color-text-muted)]">
                {{ __('You’ve explored the core workflow. The full version is designed for real students and real revenue.') }}
            </div>
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
        <p class="text-sm text-[var(--color-text-muted)] mb-6">Progress: {{ $progressPercent }}%</p>
        @if (!empty($lesson->description))
            <div class="prose max-w-none">
                {!! nl2br(e($lesson->description)) !!}
            </div>
        @endif
            <div class="flex items-center gap-3">
                @if (!empty($prevLesson))
                    <a href="{{ route('lessons.show', [$course, $prevLesson]) }}" class="inline-flex items-center px-4 py-2 rounded-md border border-[var(--color-secondary)]/30 text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">Previous</a>
                @endif
                @if (!empty($nextLesson))
                    <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">Next</a>
                @endif
                <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-4 py-2 rounded-md border border-[var(--color-secondary)]/30 text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">Back to Course</a>
            </div>
    </article>
</x-public-layout>
