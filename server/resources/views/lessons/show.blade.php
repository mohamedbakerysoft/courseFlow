<x-public-layout :title="$lesson->title" :metaDescription="str($lesson->description)->limit(160)">
    @php
        $completionCopy = $isCompleted ? __('Completed') : __('In progress');
        $lessonStateCopy = $isCompleted
            ? __('You have already completed this lesson and can revisit it anytime.')
            : __('Continue this lesson and move to the next step when you are ready.');
        $lessonVideoUrl = $lesson->video_url;
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
        $lessonActions = collect([
            $nextLesson ? __('Continue directly into :lesson', ['lesson' => $nextLesson->title]) : null,
            ! $isCompleted ? __('Finish this lesson and keep your progress moving.') : __('Revisit the video whenever you need a refresher.'),
            __('Return to :course for the full learning path.', ['course' => $course->title]),
        ])->filter()->values();

        if (filled($lessonVideoUrl) && str($lessonVideoUrl)->contains(['youtube.com', 'youtu.be'])) {
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
    @endphp

    <div class="cf-shell cf-section pt-8 sm:pt-10 lg:pt-12">
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => $course->title, 'url' => route('courses.show', $course)],
            ['label' => $lesson->title]
        ]" />

        <div class="cf-lesson-layout">
            <div class="cf-lesson-main">
                <section class="cf-lesson-header">
                    <div class="flex flex-col gap-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="cf-lesson-title-wrap">
                                <span class="cf-lesson-kicker">{{ __('Lesson experience') }}</span>
                                <h1 class="cf-lesson-title">
                                    {{ $lesson->title }}
                                </h1>
                                <p class="cf-lesson-lead">
                                    {{ __('Inside :course, this lesson keeps the flow focused, easy to follow, and ready for the next action.', ['course' => $course->title]) }}
                                </p>
                            </div>

                            <div class="grid w-full gap-3 sm:max-w-[18rem]">
                                <div class="cf-lesson-inline-stat">
                                    <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Lesson status') }}</p>
                                    <p class="mt-2 text-xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $completionCopy }}</p>
                                </div>
                                <div class="cf-lesson-inline-stat">
                                    <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Course progress') }}</p>
                                    <p class="mt-2 text-xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $progressPercent }}%</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="cf-lesson-progress" aria-hidden="true">
                                <span style="width: {{ max(0, min(100, (int) $progressPercent)) }}%"></span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 text-sm">
                                <span class="cf-chip">{{ __('Course') }}: {{ $course->title }}</span>
                                @if ($isCompleted)
                                    <span class="cf-badge">{{ __('Completed') }}</span>
                                @else
                                    <span class="cf-badge-muted">{{ __('Keep learning') }}</span>
                                @endif
                                <span class="text-[var(--color-text-muted)]">{{ $lessonStateCopy }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="cf-lesson-video-shell">
                    <iframe
                        src="{{ $lessonVideoUrl }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                        referrerpolicy="strict-origin-when-cross-origin"
                        title="{{ $lesson->title }}"
                    ></iframe>
                </section>

                <section class="cf-panel cf-lesson-summary-card px-6 py-6 sm:px-8 sm:py-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('After the video') }}</p>
                            <h2 class="mt-2 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Understand the key ideas and decide the next step') }}</h2>
                        </div>
                        <span class="cf-floating-pill">{{ __('Progress') }}: {{ $progressPercent }}%</span>
                    </div>

                    <div class="cf-lesson-summary-grid mt-6">
                        <article class="cf-panel-soft cf-lesson-detail-card px-5 py-5">
                            <p class="cf-lesson-detail-kicker">{{ __('Lesson summary') }}</p>
                            <div class="cf-lesson-detail-copy mt-4">
                                @if (!empty($lessonSummary))
                                    <p>{{ $lessonSummary }}</p>
                                @else
                                    <p>{{ __('This lesson currently relies on the video as the main teaching material.') }}</p>
                                @endif
                            </div>
                        </article>

                        <article class="cf-panel-soft cf-lesson-detail-card px-5 py-5">
                            <p class="cf-lesson-detail-kicker">{{ __('Key takeaways') }}</p>
                            <ul class="cf-check-list mt-4">
                                @forelse ($lessonTakeaways as $lessonTakeaway)
                                    <li>{{ $lessonTakeaway }}</li>
                                @empty
                                    <li>{{ __('Watch the lesson carefully and use the notes below for the main ideas.') }}</li>
                                @endforelse
                            </ul>
                        </article>

                        <article class="cf-panel-soft cf-lesson-detail-card px-5 py-5">
                            <p class="cf-lesson-detail-kicker">{{ __('Action steps') }}</p>
                            <ul class="cf-check-list mt-4">
                                @foreach ($lessonActions as $lessonAction)
                                    <li>{{ $lessonAction }}</li>
                                @endforeach
                            </ul>
                        </article>
                    </div>
                </section>

                <section class="cf-panel cf-lesson-notes-card px-6 py-6 sm:px-8 sm:py-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Lesson notes') }}</p>
                            <h2 class="mt-2 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Notes, details, and supporting context') }}</h2>
                        </div>
                        @if ($isCompleted)
                            <div class="cf-status-message">
                                {{ __('Completed lessons stay available so you can review them at any time.') }}
                            </div>
                        @endif
                    </div>

                    <div class="cf-lesson-richtext mt-6">
                        @if (!empty($lesson->description))
                            {!! nl2br(e($lesson->description)) !!}
                        @else
                            <p>{{ __('This lesson does not include additional notes yet, so the video is the primary learning material for this step.') }}</p>
                        @endif
                    </div>
                </section>
            </div>

            <aside class="cf-lesson-sidebar">
                <div class="cf-lesson-sidebar-sticky">
                    <div class="cf-lesson-sidebar-card cf-lesson-sidebar-primary">
                        <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Navigation') }}</p>
                        <h2 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Keep learning without losing context') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Move through the lesson path with a clear previous step, next step, and course overview nearby.') }}</p>

                        <div class="cf-lesson-nav-grid mt-6">
                            @if (!empty($prevLesson))
                                <a href="{{ route('lessons.show', [$course, $prevLesson]) }}" class="cf-button-secondary w-full justify-center">
                                    {{ __('Previous Lesson') }}
                                </a>
                            @endif

                            @if (!empty($nextLesson))
                                <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="cf-button-primary w-full justify-center">
                                    {{ __('Next Lesson') }}
                                </a>
                                <div class="cf-lesson-next-card">
                                    <p>{{ __('Up next') }}</p>
                                    <h3>{{ $nextLesson->title }}</h3>
                                </div>
                            @endif

                            <a href="{{ route('courses.show', $course) }}" class="cf-button-ghost w-full justify-center">
                                {{ __('Back to Course') }}
                            </a>
                        </div>
                    </div>

                    <div class="cf-lesson-sidebar-card">
                        <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Current lesson') }}</p>
                        <h3 class="mt-3 text-xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $lesson->title }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Part of :course with progress tracking and a cleaner student experience from video to next action.', ['course' => $course->title]) }}</p>

                        <div class="mt-5 grid gap-3">
                            <div class="cf-panel-soft px-4 py-4">
                                <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('State') }}</p>
                                <p class="mt-2 text-lg font-bold text-[var(--color-text-primary)]">{{ $completionCopy }}</p>
                            </div>
                            <div class="cf-panel-soft px-4 py-4">
                                <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Progress inside course') }}</p>
                                <p class="mt-2 text-lg font-bold text-[var(--color-text-primary)]">{{ $progressPercent }}%</p>
                            </div>
                            <div class="cf-panel-soft px-4 py-4">
                                <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Lesson title') }}</p>
                                <p class="mt-2 text-base font-semibold leading-7 text-[var(--color-text-primary)]">{{ $lesson->title }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-public-layout>
