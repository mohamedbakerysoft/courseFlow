<x-public-layout :title="$heroTitle" :metaDescription="$heroSubtitle">
    @php
        $instructorAvatar = $instructor?->profile_image_url ?? \App\Support\MediaAsset::avatarFallback($instructorName ?? 'Instructor');
        $instructorAvatarFallback = $instructor?->profile_image_fallback_url ?? \App\Support\MediaAsset::avatarFallback($instructorName ?? 'Instructor');
        $heroHighlights = array_values(array_filter([
            $landingCopy['hero_highlight_1'] ?? null,
            $landingCopy['hero_highlight_2'] ?? null,
            $landingCopy['hero_highlight_3'] ?? null,
        ]));
        $testimonials = $landingTestimonials ?? [];
        $faqItems = $landingFaqs ?? [];
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
        <section id="hero" class="cf-shell cf-section pt-8 sm:pt-12 lg:pt-14">
            <div class="cf-hero-shell">
                <div class="cf-hero-main lg:grid-cols-[0.82fr,1.18fr] lg:gap-16">
                    <div class="cf-hero-copy space-y-8">
                        <div class="space-y-5">
                            <span class="cf-kicker">
                                {{ $landingCopy['hero_kicker'] ?? __('Independent course business platform') }}
                            </span>

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
                                        <p class="text-sm text-[var(--color-text-muted)]">{{ $instructorTitle !== '' ? $instructorTitle : __('Founder of CourseFlow') }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-5">
                                <h1 class="cf-display max-w-[11ch]" style="font-size: var(--hero-title-size);">
                                    {{ $heroTitle }}
                                </h1>

                                <div class="cf-hero-accent"></div>

                                <p class="max-w-2xl text-lg leading-8 text-[var(--color-text-muted)]" style="font-size: clamp(1.02rem, 1.34vw, var(--hero-subtitle-size));">
                                    {{ $heroSubtitle }}
                                </p>

                                @if (!empty($instructorBio) && $showAboutInstructor)
                                    <p class="max-w-xl text-sm leading-7 text-[var(--color-text-muted)]" style="font-size: var(--hero-description-size);">
                                        {{ __('Led by :name. :bio', ['name' => $instructorName, 'bio' => str($instructorBio)->limit(130)]) }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-3 text-sm">
                                @foreach ($heroHighlights as $highlight)
                                    <span class="cf-hero-pill">{{ $highlight }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('courses.index') }}" class="cf-button-primary sm:min-w-[220px]">
                                {{ __('Browse Courses') }}
                            </a>
                            <a href="{{ route('instructor.show') }}" class="cf-button-secondary sm:min-w-[220px]">
                                {{ __('Meet the Instructor') }}
                            </a>
                        </div>
                    </div>

                    <div class="cf-hero-visual relative">
                        <div class="pointer-events-none absolute -inset-6 rounded-[42px] bg-[radial-gradient(circle_at_center,_rgba(245,184,0,0.22),_transparent_60%)] blur-3xl"></div>
                        <div class="cf-hero-media">
                            <div class="cf-hero-video-stack">
                                <div class="cf-hero-video-header">
                                <div class="cf-hero-video-copy">
                                        <p>{{ $landingCopy['hero_video_eyebrow'] ?? __('Platform walkthrough') }}</p>
                                        <p>{{ $landingCopy['hero_video_title'] ?? __('See the storefront, course page, and enrollment flow together in one clean preview.') }}</p>
                                    </div>
                                    <span class="cf-floating-pill">{{ $landingCopy['hero_video_badge'] ?? __('YouTube-ready showcase') }}</span>
                                </div>

                                <div class="cf-hero-video-stage">
                                    <img
                                        src="{{ $heroImageUrl ?? asset('images/demo/real/hero-formal-2.jpg') }}"
                                        alt="{{ __('Formal instructor portrait') }}"
                                        fetchpriority="high"
                                        loading="eager"
                                        decoding="async"
                                        style="object-position: {{ $heroImageFocus ?? 'center' }}; aspect-ratio: {{ $heroImageRatio ?? '4/5' }}; {{ $heroImageWidth ? 'width: '.$heroImageWidth.'px;' : '' }} {{ $heroImageHeight ? 'height: '.$heroImageHeight.'px;' : '' }}"
                                        class="cf-hero-video-poster {{ $heroImageMode === 'contain' ? 'object-contain' : 'object-cover' }} transition-transform duration-700 ease-out group-hover:scale-[1.03]"
                                        onerror="this.onerror=null;this.src='{{ asset('images/demo/real/hero-formal-2.jpg') }}';"
                                    >

                                    <div class="cf-hero-video-frame">
                                        <iframe
                                            src="{{ $heroVideoEmbedUrl }}"
                                            title="{{ __('CourseFlow video preview') }}"
                                            loading="lazy"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin"
                                            allowfullscreen
                                        ></iframe>
                                    </div>
                                </div>

                                <div class="cf-hero-video-footer">
                                    <div class="cf-hero-video-note">
                                        <p>{{ $landingCopy['hero_note_1_title'] ?? __('What users notice') }}</p>
                                        <p>{{ $landingCopy['hero_note_1_body'] ?? __('A clearer hero, stronger hierarchy, and immediate proof of the product in action.') }}</p>
                                    </div>
                                    <div class="cf-hero-video-note">
                                        <p>{{ $landingCopy['hero_note_2_title'] ?? __('Why it converts') }}</p>
                                        <p>{{ $landingCopy['hero_note_2_body'] ?? __('Visitors understand the offer faster when the story and the interface line up in the first screen.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cf-hero-footer-band">
                    @forelse (($heroCourses ?? collect())->take(4) as $heroCourse)
                        <a href="{{ route('courses.show', $heroCourse) }}" class="cf-hero-course-card">
                            <div class="cf-hero-course-media">
                                <img
                                    src="{{ $heroCourse->thumbnail_url }}"
                                    alt="{{ $heroCourse->title }}"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ $heroCourse->thumbnail_fallback_url }}';"
                                >
                            </div>
                            <div class="cf-hero-course-copy">
                                <p>{{ $heroCourse->language === 'ar' ? __('Arabic course') : __('Latest course') }}</p>
                                <h3>{{ $heroCourse->title }}</h3>
                                <span>
                                    {{ $heroCourse->is_free ? __('Free download-ready access') : number_format((float) $heroCourse->price, 2).' '.strtoupper($heroCourse->currency ?? 'USD') }}
                                    ·
                                    {{ trans_choice(':count lessons', $heroCourse->lessons_count ?? 0, ['count' => $heroCourse->lessons_count ?? 0]) }}
                                </span>
                            </div>
                        </a>
                    @empty
                        @foreach ($platformStats as $stat)
                            <div class="cf-hero-footer-card">
                                <p>{{ $stat['label'] }}</p>
                                <p>{{ $stat['value'] }}</p>
                            </div>
                        @endforeach
                    @endforelse
                </div>
            </div>
        </section>
    @endif

    <section id="platform-proof" class="cf-shell pb-8 sm:pb-10">
        <x-public.trust-bar :copy="$landingCopy" />
    </section>

    @if ($showCoursesPreview)
        <section class="cf-shell cf-section pt-8">
            <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl space-y-3">
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

            <div class="cf-card-grid">
                @forelse ($featuredCourses as $course)
                    <x-course.card :course="$course" />
                @empty
                    <div class="cf-panel px-8 py-10 text-center text-[var(--color-text-muted)]">
                        {{ __('No courses available yet') }}
                    </div>
                @endforelse
            </div>
        </section>
    @endif

    <section class="cf-shell cf-section">
        <div class="grid gap-6 lg:grid-cols-[0.82fr,1.18fr] lg:items-start">
            <div class="space-y-4">
                <span class="cf-kicker">{{ $landingCopy['problem_kicker'] ?? __('Problem to solution') }}</span>
                <h2 class="cf-heading">{{ $landingCopy['problem_title'] ?? __('Turn a scattered course storefront into a focused buying and learning experience') }}</h2>
                <p class="cf-subheading">{{ $landingCopy['problem_subtitle'] ?? __('The platform brings your catalog, checkout, curriculum, and instructor credibility into one clear journey.') }}</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-3">
                @foreach ($features as $feature)
                    <article class="cf-panel px-6 py-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[var(--color-primary)]/18 text-xl text-[var(--color-secondary)]">
                            {{ $feature['icon'] }}
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-[var(--color-text-primary)]">{{ $feature['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ $feature['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cf-shell pb-6">
        <x-public.social-proof :copy="$landingCopy" />
    </section>

    <section class="cf-shell cf-section pt-6">
        <div class="grid gap-6 lg:grid-cols-[0.92fr,1.08fr] lg:items-center">
            <div class="cf-panel px-6 py-6 sm:px-8 sm:py-8">
                <div class="flex items-center gap-4">
                    <img
                        src="{{ $instructorAvatar }}"
                        alt="{{ $instructorName }}"
                        class="h-20 w-20 rounded-[24px] object-cover"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='{{ $instructorAvatarFallback }}';"
                    >
                    <div>
                        <span class="cf-kicker">{{ $landingCopy['instructor_kicker'] ?? __('Instructor credibility') }}</span>
                        <h2 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $instructorName }}</h2>
                        <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ $instructorTitle !== '' ? $instructorTitle : __('Independent course creator') }}</p>
                    </div>
                </div>
                <p class="mt-6 max-w-2xl text-[15px] leading-8 text-[var(--color-text-muted)]">
                    {{ ($instructorBio !== '' && $showAboutInstructor) ? $instructorBio : __('Presenting a real instructor profile, clear expertise, and a focused course lineup improves trust before checkout.') }}
                </p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('instructor.show') }}" class="cf-button-secondary">{{ __('View Instructor Profile') }}</a>
                    <a href="{{ route('courses.index') }}" class="cf-button-primary">{{ __('Explore Courses') }}</a>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <article class="cf-panel-soft px-5 py-5">
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $landingCopy['instructor_card_1_title'] ?? __('Premium presentation') }}</p>
                    <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ $landingCopy['instructor_card_1_body'] ?? __('A cleaner public identity improves the first impression and supports conversion.') }}</p>
                </article>
                <article class="cf-panel-soft px-5 py-5">
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $landingCopy['instructor_card_2_title'] ?? __('Direct call to action') }}</p>
                    <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ $landingCopy['instructor_card_2_body'] ?? __('Students can move from discovery to enrollment without dead ends or clutter.') }}</p>
                </article>
                <article class="cf-panel-soft px-5 py-5">
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $landingCopy['instructor_card_3_title'] ?? __('Consistent experience') }}</p>
                    <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ $landingCopy['instructor_card_3_body'] ?? __('The same visual system carries from landing page to course page to dashboard.') }}</p>
                </article>
            </div>
        </div>
    </section>

    @if ($showTestimonials)
        <section class="cf-shell cf-section">
            <div class="mb-8 max-w-2xl space-y-3">
                <span class="cf-kicker">{{ $landingCopy['testimonials_kicker'] ?? __('Testimonials') }}</span>
                <h2 class="cf-heading">{{ $landingCopy['testimonials_title'] ?? __('What users notice when the storefront finally feels premium') }}</h2>
                <p class="cf-subheading">{{ $landingCopy['testimonials_subtitle'] ?? __('These signals matter because strong visual clarity improves trust before users commit to payment or enrollment.') }}</p>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <article class="cf-panel px-6 py-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[var(--color-secondary)] text-sm font-semibold text-white">
                                {{ Str::substr($testimonial['name'], 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-[var(--color-text-primary)]">{{ $testimonial['name'] }}</p>
                                <p class="text-sm text-[var(--color-text-muted)]">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                        <p class="mt-5 text-sm leading-7 text-[var(--color-text-muted)]">"{{ $testimonial['quote'] }}"</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="cf-shell cf-section pt-6">
        <div class="grid gap-6 lg:grid-cols-[0.82fr,1.18fr]">
            <div class="space-y-4">
                <span class="cf-kicker">{{ $landingCopy['faq_kicker'] ?? __('Frequently asked questions') }}</span>
                <h2 class="cf-heading">{{ $landingCopy['faq_title'] ?? __('Remove friction before users reach the buy decision') }}</h2>
                <p class="cf-subheading">{{ $landingCopy['faq_subtitle'] ?? __('A premium course product answers practical questions early, keeps pricing clear, and makes the next step obvious.') }}</p>
            </div>
            <div class="space-y-4">
                @foreach ($faqItems as $faqItem)
                    <details class="cf-faq-item" @if ($loop->first) open @endif>
                        <summary class="cf-faq-summary">{{ $faqItem['question'] }}</summary>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ $faqItem['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    @if ($showContactForm === true)
        <section id="contact" class="cf-shell cf-section pt-4">
            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif
            @error('captcha')
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $message }}
                </div>
            @enderror
            <div class="grid gap-6 lg:grid-cols-[0.8fr,1.2fr]">
                <div class="space-y-4">
                    <span class="cf-kicker">{{ $landingCopy['contact_kicker'] ?? __('Get in touch') }}</span>
                    <h2 class="cf-heading">{{ $landingCopy['contact_title'] ?? __('Ask a question before you enroll') }}</h2>
                    <p class="cf-subheading">{{ $landingCopy['contact_subtitle'] ?? __('Use this section for support, custom requests, or questions about which course to start with.') }}</p>
                </div>
                <form id="contactForm" method="POST" action="{{ route('contact.submit') }}" class="cf-panel space-y-5 px-6 py-6 sm:px-8 sm:py-8">
                    @csrf
                    <div>
                        <label for="contact_name" class="mb-2 block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Name') }}</label>
                        <input id="contact_name" name="name" type="text" class="cf-input" value="{{ old('name') }}" required>
                        @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="contact_email" class="mb-2 block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Email') }}</label>
                        <input id="contact_email" name="email" type="email" class="cf-input" value="{{ old('email') }}" required>
                        @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="contact_message" class="mb-2 block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Message') }}</label>
                        <textarea id="contact_message" name="message" rows="5" class="cf-input">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <input type="hidden" id="captcha_token" name="captcha_token" value="">
                    <div class="flex justify-end">
                        <button type="submit" class="cf-button-primary">{{ __('Send message') }}</button>
                    </div>
                </form>
                @php $siteKey = config('services.recaptcha.site_key'); @endphp
                @if (!empty($siteKey))
                    <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var form = document.getElementById('contactForm');
                            if (!form) return;
                            form.addEventListener('submit', function (e) {
                                if (typeof grecaptcha === 'undefined') return;
                                e.preventDefault();
                                grecaptcha.ready(function () {
                                    grecaptcha.execute('{{ $siteKey }}', {action: 'contact'}).then(function (token) {
                                        var input = document.getElementById('captcha_token');
                                        if (input) input.value = token;
                                        form.submit();
                                    });
                                });
                            }, { passive: false });
                        });
                    </script>
                @endif
            </div>
        </section>
    @endif

    @if ($showFooterCta)
        <section class="cf-shell pb-14 sm:pb-16 lg:pb-20">
            <div class="cf-panel-dark px-6 py-8 sm:px-10 sm:py-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-3">
                        <span class="cf-dark-kicker">
                            {{ $landingCopy['footer_kicker'] ?? __('Your next step') }}
                        </span>
                        <h2 class="cf-dark-title text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                            {{ $landingCopy['footer_title'] ?? __('Present your courses like a premium product and make the next action obvious') }}
                        </h2>
                        <p class="cf-dark-copy text-base leading-7">
                            {{ $landingCopy['footer_body'] ?? __('Clear messaging, stronger hierarchy, and a simpler CTA structure help this platform feel much closer to a sellable product.') }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('courses.index') }}" class="cf-button-primary">{{ __('Browse Courses') }}</a>
                        <a href="{{ route('login') }}" class="cf-dark-link-card !inline-flex !items-center !justify-center !rounded-full !px-6 !py-3.5">{{ __('Preview the account flow') }}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
</x-public-layout>
