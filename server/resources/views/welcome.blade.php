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
            <div class="relative overflow-hidden rounded-[32px] border border-black/[0.04] bg-white px-6 py-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:border-white/[0.08] dark:bg-[#0b0b0b] sm:px-10 sm:py-16 lg:px-12 lg:py-20">
                <div class="pointer-events-none absolute -top-40 left-1/2 h-[300px] w-[500px] -translate-x-1/2 rounded-full bg-[#f5b800] opacity-[0.08] blur-[80px] dark:opacity-[0.06]"></div>

                <div class="relative mx-auto max-w-3xl space-y-5 text-center">
                    <span class="inline-flex items-center gap-2 rounded-full border border-black/[0.08] bg-white px-4 py-2 text-[12px] font-semibold uppercase tracking-wider text-black dark:border-white/[0.12] dark:bg-[#0b0b0b] dark:text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#f5b800]"></span>
                        {{ $landingCopy['problem_kicker'] ?? __('Problem to solution') }}
                    </span>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl lg:text-5xl lg:leading-tight">
                        {{ $landingCopy['problem_title'] ?? __('Turn a scattered course storefront into a focused buying and learning experience') }}
                    </h2>
                    <p class="mx-auto max-w-2xl text-[1.1rem] leading-relaxed text-gray-600 dark:text-white/80">
                        {{ $landingCopy['problem_subtitle'] ?? __('The platform brings your catalog, checkout, curriculum, and instructor credibility into one clear journey.') }}
                    </p>
                </div>

                <div class="relative mt-12 grid gap-6 sm:grid-cols-2 lg:mt-16 lg:grid-cols-3 lg:gap-8">
                    @foreach ($features as $feature)
                        <article class="group flex h-full flex-col gap-4 rounded-[28px] border border-black/[0.04] bg-white p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1.5 hover:border-black/10 hover:shadow-[0_12px_40px_rgb(0,0,0,0.08)] dark:border-white/[0.08] dark:bg-white/[0.03] dark:shadow-[0_8px_30px_rgb(0,0,0,0.12)] dark:hover:border-white/[0.18] dark:hover:bg-white/[0.05]">
                            <div class="flex h-12 w-12 items-center justify-center rounded-[12px] bg-[#f5b800]/15 text-xl text-[#f5b800] transition duration-300 group-hover:scale-110 group-hover:bg-[#f5b800]/25 dark:bg-[#f5b800]/10 dark:ring-1 dark:ring-[#f5b800]/20 dark:group-hover:bg-[#f5b800]/20">
                                {{ $feature['icon'] }}
                            </div>
                            <h3 class="mt-3 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $feature['title'] }}</h3>
                            <p class="text-[0.98rem] leading-relaxed text-gray-600 dark:text-white/60">{{ $feature['description'] }}</p>
                        </article>
                    @endforeach
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

</x-public-layout>
