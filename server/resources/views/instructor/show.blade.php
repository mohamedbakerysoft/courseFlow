<x-public-layout :title="$content['page_label'] ?: __('My Profile')" :metaDescription="$content['hero_subheadline'] ?? ($instructor->bio ?? '')">
    @php
        $avatar = $instructor->profile_image_url;
        $avatarFallback = $instructor->profile_image_fallback_url;
        $focusAreas = $content['focus_areas'] ?? [];
        $expectations = $content['expectations'] ?? [];
        $totalLessons = $courses->sum('lessons_count');
        $startingCourse = $courses->first();
        $socialThemes = [
            'twitter' => ['label' => 'Twitter / X', 'bg' => '#111827', 'icon' => 'x'],
            'x' => ['label' => 'Twitter / X', 'bg' => '#111827', 'icon' => 'x'],
            'linkedin' => ['label' => 'LinkedIn', 'bg' => '#0A66C2', 'icon' => 'linkedin'],
            'youtube' => ['label' => 'YouTube', 'bg' => '#FF0000', 'icon' => 'youtube'],
            'website' => ['label' => 'Website', 'bg' => '#F5B800', 'icon' => 'globe'],
            'instagram' => ['label' => 'Instagram', 'bg' => '#E1306C', 'icon' => 'instagram'],
            'facebook' => ['label' => 'Facebook', 'bg' => '#1877F2', 'icon' => 'facebook'],
        ];
    @endphp

    <section class="cf-shell cf-section pt-10 sm:pt-14">
        <div class="space-y-8">
            <div class="cf-instructor-hero">
                <div class="cf-instructor-hero-media">
                    <img
                        src="{{ $avatar }}"
                        alt="{{ $instructor->name }}"
                        class="cf-instructor-hero-avatar"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='{{ $avatarFallback }}';"
                    >
                </div>

                <div class="space-y-6">
                    <div class="space-y-4">
                        <span class="cf-kicker">{{ $content['page_label'] ?: __('My Profile') }}</span>
                        <h1 class="cf-display text-4xl sm:text-5xl">{{ $content['hero_headline'] ?: $instructor->name }}</h1>
                        <p class="cf-subheading max-w-4xl">
                            {{ $content['hero_subheadline'] ?: ($instructor->bio ?: __('Explore a focused catalog of practical courses built to help students learn faster, implement confidently, and move from information to real progress.')) }}
                        </p>
                    </div>

                    <div class="cf-instructor-highlights">
                        <article class="cf-panel-soft px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Published courses') }}</p>
                            <p class="mt-2 text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $courses->total() }}</p>
                        </article>
                        <article class="cf-panel-soft px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Available lessons') }}</p>
                            <p class="mt-2 text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $totalLessons }}</p>
                        </article>
                        <article class="cf-panel-soft px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Best for') }}</p>
                            <p class="mt-2 text-base font-semibold text-[var(--color-text-primary)]">{{ $content['best_for_text'] ?: __('Creators who want a clearer path from learning to launch') }}</p>
                        </article>
                    </div>

                    @if (!empty($focusAreas))
                        <div class="flex flex-wrap gap-2">
                            @foreach ($focusAreas as $focusArea)
                                <span class="cf-chip">{{ $focusArea }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="cf-instructor-hero-actions">
                        @if ($startingCourse)
                            <a href="{{ route('courses.show', $startingCourse) }}" class="cf-button-primary">{{ $content['primary_cta_label'] ?: __('Browse top course') }}</a>
                        @endif
                        <a href="#portfolio-courses" class="cf-button-ghost">{{ $content['secondary_cta_label'] ?: __('View all courses') }}</a>
                    </div>

                    @if (!empty($links))
                        <div class="cf-instructor-socials">
                            @foreach ($links as $label => $url)
                                @php
                                    $theme = $socialThemes[strtolower($label)] ?? ['label' => ucfirst($label), 'bg' => '#111827', 'icon' => 'globe'];
                                @endphp
                                <a
                                    href="{{ $url }}"
                                    class="cf-social-link"
                                    rel="noopener"
                                    target="_blank"
                                    style="--cf-social-bg: {{ $theme['bg'] }};"
                                    aria-label="{{ $theme['label'] }}"
                                >
                                    <span class="cf-social-link-icon" aria-hidden="true">
                                        @switch($theme['icon'])
                                            @case('linkedin')
                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 8.5H3.56V20h3.38V8.5Zm.22-3.55c0-1.02-.77-1.83-1.92-1.83S3.3 3.93 3.3 4.95c0 1 .75 1.83 1.88 1.83h.02c1.17 0 1.94-.83 1.94-1.83ZM20.7 20h-3.38v-6.15c0-1.54-.55-2.59-1.94-2.59-1.06 0-1.69.72-1.97 1.41-.1.25-.13.6-.13.95V20H9.9s.04-10.45 0-11.5h3.38v1.63c.45-.69 1.25-1.68 3.05-1.68 2.23 0 3.9 1.46 3.9 4.59V20Z"/></svg>
                                                @break
                                            @case('youtube')
                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3.02 3.02 0 0 0-2.12-2.14C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.38.56A3.02 3.02 0 0 0 .5 6.2 31.3 31.3 0 0 0 0 12a31.3 31.3 0 0 0 .5 5.8 3.02 3.02 0 0 0 2.12 2.14c1.88.56 9.38.56 9.38.56s7.5 0 9.38-.56a3.02 3.02 0 0 0 2.12-2.14A31.3 31.3 0 0 0 24 12a31.3 31.3 0 0 0-.5-5.8ZM9.6 15.74V8.26L16.04 12 9.6 15.74Z"/></svg>
                                                @break
                                            @case('instagram')
                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.8A3.95 3.95 0 0 0 3.8 7.75v8.5a3.95 3.95 0 0 0 3.95 3.95h8.5a3.95 3.95 0 0 0 3.95-3.95v-8.5a3.95 3.95 0 0 0-3.95-3.95h-8.5Zm8.93 1.35a1.12 1.12 0 1 1 0 2.24 1.12 1.12 0 0 1 0-2.24ZM12 6.85A5.15 5.15 0 1 1 6.85 12 5.15 5.15 0 0 1 12 6.85Zm0 1.8A3.35 3.35 0 1 0 15.35 12 3.35 3.35 0 0 0 12 8.65Z"/></svg>
                                                @break
                                            @case('facebook')
                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-8.1h2.72l.41-3.15H13.5V7.74c0-.91.25-1.53 1.56-1.53h1.67V3.39c-.29-.04-1.28-.12-2.43-.12-2.41 0-4.06 1.47-4.06 4.17v2.31H7.5v3.15h2.74V21h3.26Z"/></svg>
                                                @break
                                            @case('x')
                                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3H21l-4.59 5.25L21.8 21h-4.22l-3.3-4.32L10.5 21H8.4l4.92-5.62L2.8 3h4.28l3 3.95L13.5 3h2.1Zm-1.48 16.42h1.17L6.16 4.5H4.9l12.52 14.92Z"/></svg>
                                                @break
                                            @default
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21c4.97 0 9-4.03 9-9s-4.03-9-9-9-9 4.03-9 9 4.03 9 9 9Z"/><path d="M3 12h18"/><path d="M12 3a15.3 15.3 0 0 1 0 18"/><path d="M12 3a15.3 15.3 0 0 0 0 18"/></svg>
                                        @endswitch
                                    </span>
                                    <span>{{ $theme['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="cf-panel px-6 py-6 sm:px-8 sm:py-8" id="portfolio-courses">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="cf-kicker">{{ $content['catalog_label'] ?: __('My courses') }}</span>
                        <h2 class="mt-3 text-2xl font-semibold tracking-[-0.03em] text-[var(--color-text-primary)]">{{ $content['catalog_heading'] ?: __('Learn with me') }}</h2>
                        <p class="mt-2 max-w-3xl text-sm leading-7 text-[var(--color-text-muted)]">{{ $content['catalog_description'] ?: __('Browse the published course library below to find the right entry point for your current level and goals.') }}</p>
                    </div>
                </div>

                <div class="cf-instructor-course-grid">
                    @forelse ($courses as $course)
                        <x-course.card :course="$course" />
                    @empty
                        <div class="cf-panel border-dashed p-6 text-center">
                            <p class="font-medium text-[var(--color-text-muted)]">
                                {{ __('No published courses yet. Published listings will appear here.') }}
                            </p>
                        </div>
                    @endforelse
                </div>

                @if ($courses->hasPages())
                    <div class="cf-pagination-shell mt-8">
                        <div class="text-sm text-[var(--color-text-muted)]">
                            {{ __('Showing :from-:to of :total courses', ['from' => $courses->firstItem(), 'to' => $courses->lastItem(), 'total' => $courses->total()]) }}
                        </div>

                        <nav aria-label="{{ __('Portfolio courses pagination') }}" class="flex flex-wrap items-center gap-2">
                            @if ($courses->onFirstPage())
                                <span class="cf-pagination-link is-disabled">{{ __('Previous') }}</span>
                            @else
                                <a href="{{ $courses->previousPageUrl() }}" class="cf-pagination-link">{{ __('Previous') }}</a>
                            @endif

                            @foreach ($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
                                @if ($page === $courses->currentPage())
                                    <span aria-current="page" class="cf-pagination-link is-active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="cf-pagination-link">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($courses->hasMorePages())
                                <a href="{{ $courses->nextPageUrl() }}" class="cf-pagination-link">{{ __('Next') }}</a>
                            @else
                                <span class="cf-pagination-link is-disabled">{{ __('Next') }}</span>
                            @endif
                        </nav>
                    </div>
                @endif
            </div>

            @if (!empty($expectations) || !empty($focusAreas))
                <div class="grid gap-6 lg:grid-cols-2">
                    @if (!empty($expectations))
                        <div class="cf-panel px-6 py-6 sm:px-8">
                            <span class="cf-kicker">{{ $content['expectations_label'] ?: __('Why learn here') }}</span>
                            <h2 class="mt-4 text-2xl font-semibold tracking-[-0.03em] text-[var(--color-text-primary)]">{{ $content['expectations_heading'] ?: __('What students can expect') }}</h2>
                            <ul class="cf-check-list mt-5">
                                @foreach ($expectations as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (!empty($focusAreas))
                        <div class="cf-panel px-6 py-6 sm:px-8">
                            <span class="cf-kicker">{{ __('Focus areas') }}</span>
                            <div class="mt-5 flex flex-wrap gap-2">
                                @foreach ($focusAreas as $focusArea)
                                    <span class="cf-chip">{{ $focusArea }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
