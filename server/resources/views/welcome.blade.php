<x-public-layout :title="$heroTitle" :metaDescription="$heroSubtitle">
    @php
        $instructorAvatar = $instructor?->profile_image_url ?? \App\Support\MediaAsset::avatarFallback($instructorName ?? 'Instructor');
        $instructorAvatarFallback = $instructor?->profile_image_fallback_url ?? \App\Support\MediaAsset::avatarFallback($instructorName ?? 'Instructor');
        $testimonials = $landingTestimonials ?? [];
        $heroVideoSource = trim((string) ($heroVideoUrl ?? ($instructorLinks['youtube'] ?? '')));
        $heroVideoId = null;

        if ($heroVideoSource !== '') {
            if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $heroVideoSource, $matches)) {
                $heroVideoId = $matches[1];
            } else {
                parse_str(parse_url($heroVideoSource, PHP_URL_QUERY) ?: '', $queryParams);
                if (!empty($queryParams['v']) && preg_match('/^[A-Za-z0-9_-]{11}$/', $queryParams['v'])) {
                    $heroVideoId = $queryParams['v'];
                }
            }
        }

        $heroVideoEmbedUrl = $heroVideoId
            ? sprintf('https://www.youtube.com/embed/%s?rel=0&modestbranding=1&playsinline=1', $heroVideoId)
            : 'https://www.youtube.com/embed/ysz5S6PUM-U?rel=0&modestbranding=1&playsinline=1';
    @endphp

    @if ($showHero)
        <section id="hero" class="cf-shell pt-6 pb-6 sm:pt-10 sm:pb-8 lg:pt-14 lg:pb-10">
            <div class="cf-hero-shell">
                <div class="cf-hero-main">
                    <div class="cf-hero-copy space-y-6">
                        <div class="space-y-5">
                            <div class="space-y-5">
                                <h1 class="cf-display max-w-[19ch]" style="font-size: var(--hero-title-size);">
                                    {{ $heroTitle }}
                                </h1>

                                <div class="cf-hero-accent"></div>

                                <p class="max-w-[37rem] text-[var(--color-text-muted)]" style="font-size: clamp(1.12rem, 1.5vw, 1.45rem); line-height: 1.62;">
                                    {{ $heroSubtitle }}
                                </p>

                                @if (!empty($instructorBio) && $showAboutInstructor)
                                    @if (!empty($instructorName))
                                        <div class="cf-hero-instructor">
                                            <img
                                                src="{{ $instructorAvatar }}"
                                                alt="{{ $instructorName }}"
                                                class="h-12 w-12 rounded-full object-cover"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='{{ $instructorAvatarFallback }}';"
                                            >
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $instructorName }}</p>
                                                <p class="text-sm text-[var(--color-text-muted)]">{{ $instructorTitle !== '' ? $instructorTitle : __('Founder of Learnova') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <p class="max-w-xl text-sm leading-7 text-[var(--color-text-muted)]" style="font-size: var(--hero-description-size);">
                                        {{ __('Led by :name. :bio', ['name' => $instructorName, 'bio' => str($instructorBio)->limit(130)]) }}
                                    </p>
                                @endif
                            </div>

                            <div class="cf-hero-action-band pt-1">
                                <a href="{{ route('courses.index') }}" class="cf-button-primary sm:min-w-[220px]">
                                    {{ __('Browse Courses') }}
                                </a>
                                <a href="{{ route('instructor.show') }}" class="cf-button-secondary sm:min-w-[220px]">
                                    {{ __('Meet the Instructor') }}
                                </a>
                            </div>

                            <span class="cf-kicker">
                                {{ $landingCopy['hero_kicker'] ?? __('Independent course business platform') }}
                            </span>
                        </div>
                    </div>

                    <div class="cf-hero-visual relative">
                        <div class="pointer-events-none absolute -inset-6 rounded-[42px] bg-[radial-gradient(circle_at_center,_rgba(245,184,0,0.22),_transparent_60%)] blur-3xl"></div>
                        <div class="relative space-y-5">
                            <div class="cf-hero-video-header">
                                <div class="cf-hero-video-copy">
                                    <p>{{ $landingCopy['hero_video_eyebrow'] ?? __('Platform walkthrough') }}</p>
                                    <p>{{ $landingCopy['hero_video_title'] ?? __('See the storefront, course page, and enrollment flow together in one clean preview.') }}</p>
                                </div>
                                <span class="cf-floating-pill">{{ $landingCopy['hero_video_badge'] ?? __('YouTube-ready showcase') }}</span>
                            </div>

                            <div class="cf-hero-video-frame overflow-hidden rounded-[1.45rem] border border-[rgba(11,11,11,0.09)] bg-black shadow-[0_18px_42px_rgba(11,11,11,0.12)]">
                                <iframe
                                    src="{{ $heroVideoEmbedUrl }}"
                                    title="{{ __('Learnova video preview') }}"
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen
                                ></iframe>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($showCoursesPreview)
        <section class="cf-shell pt-4 pb-10 sm:pt-5 sm:pb-12 lg:pt-6 lg:pb-14">
            <div class="cf-section-shell">
                <div class="cf-section-header !mb-6 sm:!mb-7">
                    <div class="max-w-2xl space-y-2.5">
                        <span class="cf-kicker">{{ $landingCopy['courses_kicker'] ?? __('Course catalog') }}</span>
                        <h2 class="cf-heading">{{ $landingCopy['courses_title'] ?? __('Featured courses') }}</h2>
                        <p class="cf-subheading">{{ $landingCopy['courses_subtitle'] ?? __('Pick a flagship program, a focused quickstart, or a practical specialty course from one clear catalog.') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="cf-chip">{{ __('Self-paced learning') }}</span>
                        <span class="cf-chip">{{ __('One-time payment') }}</span>
                        <span class="cf-chip">{{ __('Instant enrollment') }}</span>
                    </div>
                </div>

                <div class="cf-card-grid items-start">
                    @forelse ($featuredCourses as $course)
                        <x-course.card :course="$course" />
                    @empty
                        <div class="cf-panel px-8 py-10 text-center text-[var(--color-text-muted)]">
                            {{ __('No courses available yet') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    @endif

    @if ($showPlatformProof)
        <section id="platform-proof" class="cf-shell pb-6 sm:pb-8 lg:pb-9">
            <x-public.trust-bar :copy="$landingCopy" :courses="$featuredCourses" />
        </section>
    @endif

    @if ($showProblemSection)
        <section class="cf-shell pb-10 pt-4 sm:pb-12 sm:pt-5 lg:pb-14 lg:pt-6">
            <div class="cf-section-shell">
                <div class="grid gap-6 lg:grid-cols-[0.8fr,1.2fr] lg:items-start">
                    <div class="space-y-3">
                        <span class="cf-kicker">{{ $landingCopy['problem_kicker'] ?? __('Problem to solution') }}</span>
                        <h2 class="cf-heading">{{ $landingCopy['problem_title'] ?? __('Turn a scattered course storefront into a focused buying and learning experience') }}</h2>
                        <p class="cf-subheading">{{ $landingCopy['problem_subtitle'] ?? __('The platform brings your catalog, checkout, curriculum, and instructor credibility into one clear journey.') }}</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        @foreach ($features as $feature)
                            <article class="cf-feature-card">
                                <div class="cf-feature-icon">
                                    {{ $feature['icon'] }}
                                </div>
                                <h3>{{ $feature['title'] }}</h3>
                                <p>{{ $feature['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($showFlowSection)
        <section class="cf-shell pb-8 pt-1 sm:pb-10 lg:pb-12">
            <x-public.social-proof :copy="$landingCopy" />
        </section>
    @endif

    @if ($showTestimonials)
        <section class="cf-shell pb-10 pt-2 sm:pb-12 sm:pt-3 lg:pb-14 lg:pt-4">
            <div class="cf-section-shell">
                <div class="mb-6 max-w-2xl space-y-2.5 sm:mb-7">
                    <span class="cf-kicker">{{ $landingCopy['testimonials_kicker'] ?? __('Testimonials') }}</span>
                    <h2 class="cf-heading">{{ $landingCopy['testimonials_title'] ?? __('What instructors and students notice once the storefront feels professional') }}</h2>
                    <p class="cf-subheading">{{ $landingCopy['testimonials_subtitle'] ?? __('Clear feedback from real buyers and instructors helps new visitors trust the teaching experience before they enroll.') }}</p>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach (collect($testimonials)->take(6) as $testimonial)
                        <article class="cf-testimonial-card">
                            <div class="flex items-center gap-4">
                                <img
                                    src="{{ $testimonial['avatar'] }}"
                                    alt="{{ $testimonial['name'] }}"
                                    class="cf-testimonial-avatar"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ \App\Support\MediaAsset::avatarFallback($testimonial['name']) }}';"
                                >
                                <div>
                                    <p class="text-[1.05rem] font-semibold text-[var(--color-text-primary)]">{{ $testimonial['name'] }}</p>
                                    <p class="text-[0.95rem] text-[var(--color-text-muted)]">{{ $testimonial['role'] }}</p>
                                </div>
                            </div>
                            <p class="mt-4 text-[1rem] leading-8 text-[var(--color-text-primary)]/85">"{{ $testimonial['quote'] }}"</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($showFooterCta)
        <section class="cf-shell pb-12 sm:pb-14 lg:pb-16">
            <div class="cf-panel-dark px-6 py-8 sm:px-9 sm:py-9">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-2.5">
                        <span class="cf-dark-kicker">
                            {{ $landingCopy['footer_kicker'] ?? __('Launch with confidence') }}
                        </span>
                        <h2 class="cf-dark-title text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                            {{ $landingCopy['footer_title'] ?? __('Present, launch, and sell your courses with a storefront that feels premium from the first click') }}
                        </h2>
                        <p class="cf-dark-copy text-base leading-7">
                            {{ $landingCopy['footer_body'] ?? __('Give your courses a cleaner public presence, guide visitors into checkout with less friction, and help students enter the curriculum with confidence.') }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('courses.index') }}" class="cf-button-primary">{{ __('Explore Courses') }}</a>
                        <a href="{{ route('login') }}" class="cf-dark-link-card !inline-flex !items-center !justify-center !rounded-full !px-6 !py-3.5">{{ __('Preview Instructor Access') }}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($showContactForm === true)
        <div x-data="{ open: false }" class="fixed bottom-5 right-5 z-40 sm:bottom-6 sm:right-6">
            <button type="button" @click="open = !open" class="flex h-14 w-14 items-center justify-center rounded-full bg-[var(--color-primary)] text-[var(--color-primary-contrast)] shadow-[0_18px_40px_rgba(11,11,11,0.2)] transition hover:-translate-y-0.5" aria-label="{{ __('Open support chat') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m-7 6 2.6-2H19a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h1Z"/>
                </svg>
            </button>

            <div x-cloak x-show="open" x-transition.origin.bottom.right class="absolute bottom-16 right-0 w-[min(92vw,24rem)] overflow-hidden rounded-[1.4rem] border border-[rgba(11,11,11,0.08)] bg-white p-5 shadow-[0_28px_60px_rgba(11,11,11,0.16)]">
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[var(--color-text-muted)]">{{ __('Support') }}</p>
                        <h3 class="mt-2 text-xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Send a quick message') }}</h3>
                    </div>
                    <button type="button" @click="open = false" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--color-accent)] text-[var(--color-text-primary)]">
                        <span class="sr-only">{{ __('Close support chat') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/>
                        </svg>
                    </button>
                </div>

                <form id="contactForm" method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="contact_name" class="mb-2 block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Name') }}</label>
                        <input id="contact_name" name="name" type="text" class="cf-input" value="{{ old('name') }}">
                    </div>
                    <div>
                        <label for="contact_email" class="mb-2 block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Email') }}</label>
                        <input id="contact_email" name="email" type="email" class="cf-input" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="contact_message" class="mb-2 block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Message') }}</label>
                        <textarea id="contact_message" name="message" rows="4" class="cf-input">{{ old('message') }}</textarea>
                    </div>
                    <input type="hidden" id="captcha_token" name="captcha_token" value="">
                    <button type="submit" class="cf-button-primary w-full">{{ __('Send Message') }}</button>
                </form>
            </div>
        </div>
    @endif
</x-public-layout>
