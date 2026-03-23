<x-public-layout :title="$lesson->title" :metaDescription="str($lesson->description)->limit(160)">
    @php
        $lessonVideoUrl = $lesson->video_url;
        $lessonVideoFileUrl = $lesson->video_file_url;
        $hasUploadedVideo = filled($lessonVideoFileUrl);
        $lessonBody = trim((string) $lesson->description);
        $cleanLessonBody = trim(preg_replace('/\s+/', ' ', strip_tags($lessonBody)));
        $lessonParagraphs = collect(preg_split('/\r?\n\r?\n+/', $lessonBody) ?: [])
            ->map(fn ($paragraph) => trim(strip_tags($paragraph)))
            ->filter()
            ->values();
        $lessonSentences = collect(preg_split('/(?<=[.!?])\s+/', $cleanLessonBody) ?: [])
            ->map(fn ($sentence) => trim($sentence))
            ->filter()
            ->values();
        $lessonHighlights = $lessonSentences->take(4)->values();
        $lessonSummary = $lessonParagraphs->first() ?? $cleanLessonBody;
        $lessonTakeaways = collect([
            $lessonHighlights->get(0),
            $lessonHighlights->get(1),
            $lessonHighlights->get(2),
        ])->filter()->values();

        if (! $hasUploadedVideo && filled($lessonVideoUrl) && str($lessonVideoUrl)->contains(['youtube.com', 'youtu.be'])) {
            $parsedUrl = parse_url($lessonVideoUrl);
            parse_str($parsedUrl['query'] ?? '', $videoQuery);

            $videoId = match (true) {
                isset($videoQuery['v']) && filled($videoQuery['v']) => $videoQuery['v'],
                isset($parsedUrl['host']) && $parsedUrl['host'] === 'youtu.be' => ltrim($parsedUrl['path'] ?? '', '/'),
                isset($parsedUrl['path']) && str($parsedUrl['path'])->contains('/embed/') => str($parsedUrl['path'])->after('/embed/')->before('?')->value(),
                default => null,
            };

            if (filled($videoId)) {
                $lessonVideoUrl = sprintf(
                    'https://www.youtube.com/embed/%s?rel=0&modestbranding=1&playsinline=1',
                    $videoId
                );
            }
        }

        $lessonCount = $lessonItems->count();
        $completedCount = $lessonItems->where('is_completed', true)->count();
        $currentLessonNumber = ($lessonItems->search(fn ($item) => $item['is_current'] === true) ?: 0) + 1;
        $currentLessonState = $isCompleted ? __('Completed') : __('In progress');
        $currentLessonStateCopy = $isCompleted
            ? __('You completed this lesson. You can review the material any time.')
            : __('Stay focused on this lesson, then continue through the remaining curriculum.');
    @endphp

    <div class="cf-shell cf-section pt-6 sm:pt-8 lg:pt-10">
        <div class="cf-learning-layout">
            <aside class="cf-learning-sidebar">
                <div class="cf-learning-sidebar-sticky">
                    <div class="cf-learning-sidebar-panel">
                        <a href="{{ route('courses.show', $course) }}" class="cf-button-ghost w-full justify-center">
                            {{ __('Back to Course') }}
                        </a>

                        <div class="cf-learning-course-meta">
                            <p>{{ __('Course') }}</p>
                            <h2>{{ $course->title }}</h2>
                            <span>{{ __(':completed of :total lessons completed', ['completed' => $completedCount, 'total' => $lessonCount]) }}</span>
                        </div>

                        <div class="cf-lesson-progress" aria-hidden="true">
                            <span style="width: {{ max(0, min(100, (int) $progressPercent)) }}%"></span>
                        </div>

                        <div class="cf-learning-lesson-list">
                            @foreach ($lessonModules as $module)
                                <section class="space-y-3">
                                    <div class="px-1">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('Module :number', ['number' => $module->position]) }}</p>
                                        <h3 class="mt-1 text-sm font-semibold text-[var(--color-text-primary)]">{{ $module->title }}</h3>
                                    </div>

                                    <nav aria-label="{{ $module->title }}" class="space-y-2">
                                        @foreach ($module->lesson_items as $lessonItem)
                                            @php
                                                $sidebarLesson = $lessonItem['lesson'];
                                                $isCurrentItem = $lessonItem['is_current'];
                                                $isCompletedItem = $lessonItem['is_completed'];
                                                $isLockedItem = $lessonItem['is_locked'];
                                                $itemClasses = 'cf-learning-lesson-item';

                                                if ($isCurrentItem) {
                                                    $itemClasses .= ' is-current';
                                                } elseif ($isCompletedItem) {
                                                    $itemClasses .= ' is-completed';
                                                } elseif ($isLockedItem) {
                                                    $itemClasses .= ' is-locked';
                                                }
                                            @endphp

                                            @if ($isLockedItem)
                                                <div class="{{ $itemClasses }}" aria-disabled="true">
                                                    <span class="cf-learning-lesson-index">{{ str_pad((string) $sidebarLesson->position, 2, '0', STR_PAD_LEFT) }}</span>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="cf-learning-lesson-title">{{ $sidebarLesson->title }}</p>
                                                        <p class="cf-learning-lesson-state">{{ __('Locked') }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ route('lessons.show', [$course, $sidebarLesson]) }}" class="{{ $itemClasses }}">
                                                    <span class="cf-learning-lesson-index">{{ str_pad((string) $sidebarLesson->position, 2, '0', STR_PAD_LEFT) }}</span>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="cf-learning-lesson-title">{{ $sidebarLesson->title }}</p>
                                                        <p class="cf-learning-lesson-state">
                                                            {{ $isCurrentItem ? __('Current lesson') : ($isCompletedItem ? __('Completed') : __('Ready to watch')) }}
                                                        </p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </nav>
                                </section>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            <div class="cf-learning-main">
                <section class="cf-learning-stage">
                    <div class="cf-learning-stage-header">
                        <div class="space-y-4">
                            <span class="cf-lesson-kicker">{{ __('Lesson :number of :total', ['number' => $currentLessonNumber, 'total' => $lessonCount]) }}</span>
                            <div class="space-y-3">
                                <h1 class="cf-learning-title">{{ $lesson->title }}</h1>
                                <p class="cf-learning-subtitle">{{ $currentLessonStateCopy }}</p>
                            </div>
                        </div>

                        <div class="cf-learning-stage-stats">
                            <div class="cf-learning-stage-stat">
                                <p>{{ __('Status') }}</p>
                                <strong>{{ $currentLessonState }}</strong>
                            </div>
                            <div class="cf-learning-stage-stat">
                                <p>{{ __('Progress') }}</p>
                                <strong>{{ $progressPercent }}%</strong>
                            </div>
                        </div>
                    </div>

                    <div class="cf-learning-video-shell">
                        @if ($hasUploadedVideo)
                            <video controls playsinline class="h-full w-full rounded-[inherit] bg-black">
                                <source src="{{ $lessonVideoFileUrl }}" type="video/mp4">
                                {{ __('Your browser does not support HTML5 video.') }}
                            </video>
                        @else
                            <iframe
                                src="{{ $lessonVideoUrl }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                referrerpolicy="strict-origin-when-cross-origin"
                                title="{{ $lesson->title }}"
                            ></iframe>
                        @endif
                    </div>

                    <div class="cf-learning-actions">
                        <div class="cf-learning-state-pill {{ $isCompleted ? 'is-completed' : 'is-active' }}">
                            {{ $currentLessonState }}
                        </div>
                        @if (! $isCompleted)
                            <button id="markLessonCompleteBtn" type="button" class="cf-button-primary">
                                {{ __('Mark Lesson as Completed') }}
                            </button>
                        @endif
                    </div>
                </section>

                <section class="cf-learning-content-grid">
                    <article class="cf-panel cf-learning-card px-6 py-6 sm:px-8 sm:py-8">
                        <p class="cf-lesson-detail-kicker">{{ __('Lesson summary') }}</p>
                        <div class="cf-lesson-richtext mt-5">
                            @if (! empty($lessonSummary))
                                <p>{{ $lessonSummary }}</p>
                            @else
                                <p>{{ __('This lesson currently relies on the video as the main teaching material.') }}</p>
                            @endif
                        </div>
                    </article>

                    <article class="cf-panel cf-learning-card px-6 py-6 sm:px-8 sm:py-8">
                        <p class="cf-lesson-detail-kicker">{{ __('Key takeaways') }}</p>
                        <ul class="cf-check-list mt-5">
                            @forelse ($lessonTakeaways as $lessonTakeaway)
                                <li>{{ $lessonTakeaway }}</li>
                            @empty
                                <li>{{ __('Watch the lesson carefully and use the notes below for the main ideas.') }}</li>
                            @endforelse
                        </ul>
                    </article>
                </section>

                <section class="cf-panel cf-learning-card px-6 py-6 sm:px-8 sm:py-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="cf-lesson-detail-kicker">{{ __('Lesson notes') }}</p>
                            <h2 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Detailed notes and context') }}</h2>
                        </div>
                        <span class="cf-floating-pill">{{ __('Course progress') }}: {{ $progressPercent }}%</span>
                    </div>

                    <div class="cf-lesson-richtext mt-6">
                        @if (! empty($lesson->description))
                            {!! nl2br(e($lesson->description)) !!}
                        @else
                            <p>{{ __('This lesson does not include additional notes yet, so the video is the primary learning material for this step.') }}</p>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const markBtn = document.getElementById('markLessonCompleteBtn');
            if (!markBtn) return;

            let isSubmitting = false;
            markBtn.addEventListener('click', async function () {
                if (isSubmitting) return;
                isSubmitting = true;
                markBtn.disabled = true;
                markBtn.textContent = '{{ __('Saving progress...') }}';

                try {
                    const response = await fetch('{{ route('lessons.complete', [$course, $lesson]) }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({}),
                    });

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        throw new Error('Request failed');
                    }
                } catch (error) {
                    console.error(error);
                    markBtn.textContent = '{{ __('Try again') }}';
                    markBtn.disabled = false;
                }
            });
        });
    </script>
</x-public-layout>
