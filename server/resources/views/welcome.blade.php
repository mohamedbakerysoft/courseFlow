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
        <section id="hero" class="cf-shell pt-5 pb-6 sm:pt-8 sm:pb-8 lg:pt-10 lg:pb-10">
            <div class="cf-hero-shell">
                <div class="cf-hero-main">
                    <div class="cf-hero-copy space-y-6">
                        <div class="space-y-4">
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
                                        <p class="text-sm text-[var(--color-text-muted)]">{{ $instructorTitle !== '' ? $instructorTitle : __('Founder of Learnova') }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <h1 class="cf-display max-w-[12ch]" style="font-size: var(--hero-title-size);">
                                    {{ $heroTitle }}
                                </h1>

                                <div class="cf-hero-accent"></div>

                                <p class="max-w-[34rem] text-lg leading-8 text-[var(--color-text-muted)]" style="font-size: clamp(1.02rem, 1.34vw, var(--hero-subtitle-size));">
                                    {{ $heroSubtitle }}
                                </p>

                                @if (!empty($instructorBio) && $showAboutInstructor)
                                    <p class="max-w-xl text-sm leading-7 text-[var(--color-text-muted)]" style="font-size: var(--hero-description-size);">
                                        {{ __('Led by :name. :bio', ['name' => $instructorName, 'bio' => str($instructorBio)->limit(130)]) }}
                                    </p>
                                @endif
                            </div>

                            <div class="cf-hero-mobile-trust flex flex-wrap gap-3 text-sm">
                                @foreach ($heroHighlights as $highlight)
                                    <span class="cf-hero-pill">{{ $highlight }}</span>
                                @endforeach
                            </div>

                            <div class="cf-hero-action-band">
                                <a href="{{ route('courses.index') }}" class="cf-button-primary sm:min-w-[220px]">
                                    {{ __('Browse Courses') }}
                                </a>
                                <a href="{{ route('instructor.show') }}" class="cf-button-secondary sm:min-w-[220px]">
                                    {{ __('Meet the Instructor') }}
                                </a>
                            </div>
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
                </div>
            </div>
        </section>
    @endif

    @if ($showPlatformProof)
        <section id="platform-proof" class="cf-shell pb-6 sm:pb-8 lg:pb-9">
            <x-public.trust-bar :copy="$landingCopy" :courses="$featuredCourses" />
        </section>
    @endif

    @if ($showCoursesPreview)
        <section class="cf-shell pt-6 pb-10 sm:pt-7 sm:pb-12 lg:pt-8 lg:pb-14">
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

                <div class="cf-card-grid">
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
                    <h2 class="cf-heading">{{ $landingCopy['testimonials_title'] ?? __('What users notice when the storefront finally feels premium') }}</h2>
                    <p class="cf-subheading">{{ $landingCopy['testimonials_subtitle'] ?? __('These signals matter because strong visual clarity improves trust before users commit to payment or enrollment.') }}</p>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
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
                                    <p class="text-base font-semibold text-[var(--color-text-primary)]">{{ $testimonial['name'] }}</p>
                                    <p class="text-sm text-[var(--color-text-muted)]">{{ $testimonial['role'] }}</p>
                                </div>
                            </div>
                            <p class="mt-5 text-[15px] leading-8 text-[var(--color-text-muted)]">"{{ $testimonial['quote'] }}"</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($showFaqSection)
        <section class="cf-shell pb-10 pt-2 sm:pb-12 sm:pt-3 lg:pb-14 lg:pt-4">
            <div class="cf-section-shell grid gap-5 lg:grid-cols-[0.82fr,1.18fr]">
                <div class="space-y-3">
                    <span class="cf-kicker">{{ $landingCopy['faq_kicker'] ?? __('Frequently asked questions') }}</span>
                    <h2 class="cf-heading">{{ $landingCopy['faq_title'] ?? __('Remove friction before users reach the buy decision') }}</h2>
                    <p class="cf-subheading">{{ $landingCopy['faq_subtitle'] ?? __('A premium course product answers practical questions early, keeps pricing clear, and makes the next step obvious.') }}</p>
                </div>
                <div class="space-y-3">
                    @foreach ($faqItems as $faqItem)
                        <details class="cf-faq-item" @if ($loop->first) open @endif>
                            <summary class="cf-faq-summary">{{ $faqItem['question'] }}</summary>
                            <p class="mt-3 text-[15px] leading-8 text-[var(--color-text-muted)]">{{ $faqItem['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($showContactForm === true)
        <section id="contact" class="cf-shell pb-12 pt-2 sm:pb-14 sm:pt-3 lg:pb-16 lg:pt-4">
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
                <div class="cf-section-shell grid gap-5 lg:grid-cols-[0.8fr,1.2fr]">
                <div class="space-y-3">
                    <span class="cf-kicker">{{ $landingCopy['contact_kicker'] ?? __('Get in touch') }}</span>
                    <h2 class="cf-heading">{{ $landingCopy['contact_title'] ?? __('Ask a question before you enroll') }}</h2>
                    <p class="cf-subheading">{{ $landingCopy['contact_subtitle'] ?? __('Use this section for support, custom requests, or questions about which course to start with.') }}</p>
                </div>
                <form id="contactForm" method="POST" action="{{ route('contact.submit') }}" class="cf-panel space-y-4 px-6 py-6 sm:px-7 sm:py-7">
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
                    <div class="flex justify-end pt-1">
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
        <section class="cf-shell pb-12 sm:pb-14 lg:pb-16">
            <div class="cf-panel-dark px-6 py-8 sm:px-9 sm:py-9">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-2.5">
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
