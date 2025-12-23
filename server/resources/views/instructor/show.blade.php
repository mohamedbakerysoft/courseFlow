<x-public-layout :title="__('Instructor')" :metaDescription="$instructor->bio ?? ''">
    @php
        $avatar = $instructor->profile_image_url;
        $avatarFallback = $instructor->profile_image_fallback_url;
        $focusAreas = [
            __('Course launches'),
            __('Student onboarding'),
            __('Payments and enrollment'),
            __('Laravel course products'),
        ];
        $totalLessons = $courses->sum('lessons_count');
    @endphp

    <section class="cf-shell cf-section pt-10 sm:pt-14">
        <div class="grid gap-8 lg:grid-cols-[0.72fr,1.28fr]">
            <div class="cf-panel px-6 py-6 sm:px-8 sm:py-8 lg:sticky lg:top-28">
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <img
                            src="{{ $avatar }}"
                            alt="{{ $instructor->name }}"
                            class="h-28 w-28 rounded-[30px] object-cover ring-4 ring-[var(--color-primary)]/10"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='{{ $avatarFallback }}';"
                        >
                        <div>
                            <span class="cf-kicker">{{ __('Lead instructor') }}</span>
                            <h1 class="mt-3 text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $instructor->name }}</h1>
                            <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Helps independent creators turn expertise into premium, purchase-ready digital courses.') }}</p>
                        </div>
                    </div>

                    @if ($instructor->bio)
                        <div class="cf-panel-soft px-5 py-5">
                            <p class="text-[15px] leading-8 text-[var(--color-text-muted)]">{{ $instructor->bio }}</p>
                        </div>
                    @endif

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="cf-panel-soft px-4 py-4">
                            <p class="text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->count() }}</p>
                            <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ __('Published courses') }}</p>
                        </div>
                        <div class="cf-panel-soft px-4 py-4">
                            <p class="text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $totalLessons }}</p>
                            <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ __('Available lessons') }}</p>
                        </div>
                    </div>

                    <div class="cf-panel-soft px-5 py-5">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Focus areas') }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($focusAreas as $focusArea)
                                <span class="cf-chip">{{ $focusArea }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="cf-panel-soft px-5 py-5">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('What students can expect') }}</p>
                        <ul class="cf-check-list mt-4">
                            <li>{{ __('Clear lesson order and a direct path from overview to progress tracking.') }}</li>
                            <li>{{ __('Practical guidance that turns ideas into a launch-ready course business.') }}</li>
                            <li>{{ __('A storefront experience that feels human, credible, and easy to trust.') }}</li>
                        </ul>
                    </div>

                    @if (!empty($links))
                        <div class="flex flex-wrap gap-3">
                            @foreach ($links as $label => $url)
                                <a href="{{ $url }}" class="cf-button-ghost !px-4 !py-2" rel="noopener" target="_blank">{{ ucfirst($label) }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="cf-page-header">
                    <div class="relative z-[1] grid gap-6 lg:grid-cols-[0.9fr,1.1fr]">
                        <div class="space-y-4">
                            <span class="cf-dark-kicker">{{ __('Instructor profile') }}</span>
                            <h2 class="cf-dark-title text-3xl font-bold tracking-[-0.04em] sm:text-4xl">{{ __('Choose the course that matches your current business stage') }}</h2>
                            <p class="cf-dark-copy max-w-2xl text-sm leading-7">{{ __('Browse beginner-friendly quickstarts, advanced launch programs, and practical system-building courses from one focused instructor catalog.') }}</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="cf-metric">
                                <p class="cf-dark-title text-2xl font-bold">{{ __('Independent creator focus') }}</p>
                                <p class="cf-dark-copy mt-2 text-sm">{{ __('Built around clear offers, smooth enrollment, and structured student delivery.') }}</p>
                            </div>
                            <div class="cf-metric">
                                <p class="cf-dark-title text-2xl font-bold">{{ __('Trust-first course experience') }}</p>
                                <p class="cf-dark-copy mt-2 text-sm">{{ __('Every course card, course page, and profile element supports clarity before checkout.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mb-5 flex items-end justify-between gap-4">
                        <div>
                            <span class="cf-kicker">{{ __('Course catalog') }}</span>
                            <h2 class="mt-3 text-2xl font-semibold tracking-[-0.03em] text-[var(--color-text-primary)]">{{ __('Published courses') }}</h2>
                        </div>
                    </div>
                    <div class="cf-card-grid">
                        @forelse ($courses as $course)
                            <x-course.card :course="$course" />
                        @empty
                            <div class="cf-panel rounded-[28px] border-dashed p-6 text-center">
                                <p class="font-medium text-[var(--color-text-muted)]">
                                    {{ __('No published courses yet. Published listings will appear here.') }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
