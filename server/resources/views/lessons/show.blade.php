<x-public-layout :title="$lesson->title" :metaDescription="str($lesson->description)->limit(160)">
    @php
        $completionCopy = $isCompleted ? __('Completed') : __('In progress');
        $lessonStateCopy = $isCompleted
            ? __('You have already completed this lesson and can revisit it anytime.')
            : __('Continue this lesson and move to the next step when you are ready.');
        $lessonVideoUrl = $lesson->video_url;

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
                            <div class="max-w-3xl">
                                <span class="cf-lesson-kicker">{{ __('Lesson experience') }}</span>
                                <h1 class="mt-4 text-[2.4rem] font-bold tracking-[-0.055em] text-[var(--color-text-primary)] sm:text-[3rem]">
                                    {{ $lesson->title }}
                                </h1>
                                <p class="mt-4 max-w-2xl text-[1.02rem] leading-8 text-[var(--color-text-muted)]">
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

                <section class="cf-panel px-6 py-6 sm:px-8 sm:py-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Lesson notes') }}</p>
                            <h2 class="mt-2 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('What this lesson covers') }}</h2>
                        </div>
                        <span class="cf-floating-pill">{{ __('Progress') }}: {{ $progressPercent }}%</span>
                    </div>

                    @if ($isCompleted)
                        <div class="cf-status-message mt-5">
                            {{ __('You’ve explored the core workflow. The full version is designed for real instruction and student management.') }}
                        </div>
                    @endif

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
                    <div class="cf-lesson-sidebar-card">
                        <p class="text-[12px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Navigation') }}</p>
                        <h2 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Keep moving through the course') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Move back, continue to the next lesson, or return to the course overview whenever you need context.') }}</p>

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
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-public-layout>
