<x-public-layout :title="$heroTitle" :metaDescription="$heroSubtitle">
    <div class="bg-white">
        <style>
            a[href*="wa.me"] {
                z-index: 50;
                transition: transform 150ms ease, box-shadow 150ms ease;
            }
            a[href*="wa.me"]:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
            }
        </style>
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 lg:pr-0 space-y-12 lg:space-y-16 py-10 lg:py-16">
            @if ($showHero)
            <header id="hero" class="relative min-h-screen">
                <div class="relative w-full min-h-screen bg-gradient-to-br from-[var(--color-primary)]/5 via-transparent to-transparent">
                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-6 lg:gap-8 px-4 sm:px-6 lg:px-8 lg:pr-0">
                        <div class="order-2 lg:order-1 self-center space-y-4 sm:space-y-5">
                            <div class="space-y-2">
                                <p class="text-sm font-semibold uppercase tracking-wide text-[var(--color-text-muted)]">
                                    {{ app()->getLocale() === 'ar' ? 'منصة دورات لمدرّس واحد' : 'Single‑Instructor Course Platform' }}
                                </p>
                                
                                <h2 class="font-extrabold leading-tight tracking-tight text-[var(--color-text-primary)]" style="font-size: var(--hero-title-size);">
                                    {{ $heroTitle }}
                                </h2>
                                
                                @if (!empty($instructorName) && $showAboutInstructor)
                                    <p class="text-[var(--color-text-primary)] font-semibold" style="font-size: var(--hero-description-size);">
                                        {{ $instructorName }}
                                    </p>
                                @endif
                                @if (!empty($instructorTitle) && $showAboutInstructor)
                                    <p class="text-[var(--color-text-muted)]" style="font-size: var(--hero-description-size);">
                                        {{ $instructorTitle }}
                                    </p>
                                @endif
                            </div>
                            <div class="space-y-4">
                                <p class="text-[var(--color-text-muted)]" style="font-size: var(--hero-subtitle-size);">
                                    {{ $heroSubtitle }}
                                </p>
                                <p class="text-[var(--color-text-muted)]" style="font-size: var(--hero-description-size);">
                                    {{ app()->getLocale() === 'ar' ? 'دروس منظّمة مع وصول يعتمد على التسجيل' : 'Structured lessons with enrollment‑based access.' }}
                                </p>
                                <p class="text-[var(--color-text-muted)]" style="font-size: var(--hero-description-size);">
                                    {{ __('Designed for independent instructors and real students.') }}
                                </p>
                                @if (!empty($instructorBio) && $showAboutInstructor)
                                    <p class="text-[var(--color-text-muted)]" style="font-size: var(--hero-description-size);">
                                        {{ $instructorBio }}
                                    </p>
                                @endif
                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-3 pt-1">
                                <a href="{{ route('courses.index') }}"
                                   class="inline-flex justify-center items-center w-full sm:w-auto px-6 py-3 rounded-full bg-[var(--color-primary)] text-white text-base font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                    {{ app()->getLocale() === 'ar' ? 'احصل على وصول فوري' : 'Get instant access' }}
                                </a>
                                <p class="text-sm text-[var(--color-text-muted)]">
                                    {{ app()->getLocale() === 'ar' ? 'بدون اشتراك · إعداد مرة واحدة' : 'No subscription · One‑time setup' }}
                                </p>
                                </div>
                            </div>
                            @if (!empty($instructorLinks))
                                <div class="flex items-center gap-4 pt-1">
                                    @foreach ($instructorLinks as $label => $url)
                                        @if (!empty($url))
                                            <a href="{{ $url }}" class="inline-flex items-center justify-center h-9 w-9 rounded-full ring-1 ring-[var(--color-secondary)]/20 text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/10 hover:opacity-80 transition-colors" rel="noopener" target="_blank" aria-label="{{ ucfirst($label) }}">
                                                @switch($label)
                                                    @case('twitter')
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.633 7.997c.013.175.013.35.013.524 0 5.348-4.07 11.512-11.512 11.512-2.291 0-4.418-.676-6.207-1.84.318.037.624.05.955.05a8.153 8.153 0 0 0 5.058-1.742 4.077 4.077 0 0 1-3.805-2.825c.25.037.5.062.763.062.363 0 .726-.05 1.063-.137a4.07 4.07 0 0 1-3.265-4.003v-.05c.537.3 1.156.487 1.816.512a4.066 4.066 0 0 1-1.813-3.389c0-.75.2-1.45.55-2.055a11.55 11.55 0 0 0 8.39 4.257 4.58 4.58 0 0 1-.1-.934 4.07 4.07 0 0 1 7.04-2.784 8.015 8.015 0 0 0 2.58-.984 4.084 4.084 0 0 1-1.787 2.244 8.116 8.116 0 0 0 2.34-.625 8.764 8.764 0 0 1-2.03 2.11z"/></svg>
                                                        @break
                                                    @case('instagram')
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3h10zm-5 3a5 5 0 1 0 .001 10.001A5 5 0 0 0 12 7zm0 2a3 3 0 1 1-.001 6.001A3 3 0 0 1 12 9zm4.5-3a1.5 1.5 0 1 0 .001 3.001A1.5 1.5 0 0 0 16.5 6z"/></svg>
                                                        @break
                                                    @case('youtube')
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.6 3.5 12 3.5 12 3.5s-7.6 0-9.4.6A3 3 0 0 0 .5 6.2C0 8 .1 12 .1 12s-.1 4 .4 5.8a3 3 0 0 0 2.1 2.1c1.8.6 9.4.6 9.4.6s7.6 0 9.4-.6a3 3 0 0 0 2.1-2.1c.5-1.8.4-5.8.4-5.8s.1-4-.4-5.8zM9.8 15.2V8.8l6.2 3.2-6.2 3.2z"/></svg>
                                                        @break
                                                    @case('linkedin')
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM0 8.98h5v14H0v-14zM8.98 8.98h4.78v1.91h.07c.66-1.25 2.26-2.57 4.65-2.57 4.98 0 5.9 3.28 5.9 7.55v8.11h-5v-7.2c0-1.72-.03-3.93-2.4-3.93-2.4 0-2.77 1.87-2.77 3.8v7.33h-5v-14z"/></svg>
                                                        @break
                                                    @default
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>
                                                @endswitch
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="order-1 lg:order-2 flex justify-center lg:justify-end">
                            <div class="w-full h-full">
                                <div class="group relative h-[100vh] sm:h-[110vh] lg:h-screen overflow-hidden rounded-t-3xl shadow-xl ring-1 ring-[var(--color-secondary)]/10 bg-gradient-to-br from-[var(--color-primary)]/10 via-white to-[var(--color-primary)]/5">
                                    <img
                                        src="{{ $heroImageUrl ?? asset('images/demo/IMG_1700.JPG') }}"
                                        alt="{{ __('Hero Image') }}"
                                        style="object-position: {{ $heroImageFocus ?? 'center' }}; aspect-ratio: {{ $heroImageRatio ?? '16/9' }}; object-fit: cover;"
                                        class="absolute inset-0 h-full w-full {{ $heroImageMode === 'contain' ? 'object-contain' : 'object-cover' }} rounded-3xl transition-transform duration-300 ease-out group-hover:scale-[1.01] group-hover:shadow-2xl"
                                        loading="lazy"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            @endif
            <x-public.trust-bar />
            @if ($showCoursesPreview)
            <section aria-label="{{ __('Featured courses') }}" class="space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                            {{ __('Courses') }}
                        </p>
                        <h2 class="text-2xl sm:text-3xl font-semibold text-[var(--color-text-primary)]">
                            {{ __('Featured courses') }}
                        </h2>
                        <p class="text-xs text-[var(--color-text-muted)]">
                            {{ __('Showing') }} {{ $featuredCourses->count() }} {{ __('courses') }}
                        </p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center text-sm font-semibold text-[var(--color-primary)] hover:underline">
                        {{ __('View all courses') }}
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
                    @forelse ($featuredCourses as $course)
                        <x-course.card :course="$course" />
                    @empty
                        <p class="text-sm text-[var(--color-text-muted)]">{{ __('No courses available yet') }}</p>
                    @endforelse
                </div>
            </section>
            @endif
            <x-public.social-proof />


            <section aria-label="{{ __('Why this platform') }}" class="space-y-8">
                <div class="space-y-3 text-center">
                    <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">{{ __('Built for real outcomes') }}</p>
                    <h2 class="text-2xl sm:text-3xl font-semibold text-[var(--color-text-primary)]">{{ __('Why this platform works') }}</h2>
                    <p class="text-sm sm:text-base text-[var(--color-text-muted)] max-w-2xl mx-auto">{{ __('Clear frameworks, practical lessons, and focused programs designed for busy professionals who want confidence and results.') }}</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 flex flex-col items-start gap-4">
                        <div class="relative w-full">
                            <div class="mx-auto h-28 w-full rounded-xl overflow-hidden ring-1 ring-[var(--color-secondary)]/10 bg-gradient-to-tr from-[var(--color-primary)]/10 via-white to-[var(--color-accent)]/10 flex items-center justify-center">
                                <svg class="h-16 w-16 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-base font-semibold text-[var(--color-text-primary)]">{{ __('Actionable, step‑by‑step content') }}</h3>
                            <p class="text-sm text-[var(--color-text-muted)]">{{ __('Lessons focus on scripts, checklists, and real scenarios you can apply immediately.') }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 flex flex-col items-start gap-4">
                        <div class="relative w-full">
                            <div class="mx-auto h-28 w-full rounded-xl overflow-hidden ring-1 ring-[var(--color-secondary)]/10 bg-gradient-to-tr from-[var(--color-primary)]/20 via-white to-[var(--color-primary)]/10 flex items-center justify-center">
                                <svg class="h-16 w-16 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 3v10m0 0l-3-3m3 3l3-3M4 21h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-base font-semibold text-[var(--color-text-primary)]">{{ __('One‑time purchase, instant access') }}</h3>
                            <p class="text-sm text-[var(--color-text-muted)]">{{ __('No subscriptions. Buy once, own it forever, and learn at your pace.') }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 flex flex-col items-start gap-4">
                        <div class="relative w-full">
                            <div class="mx-auto h-28 w-full rounded-xl overflow-hidden ring-1 ring-[var(--color-secondary)]/10 bg-gradient-to-tr from-[var(--color-accent)]/15 via-white to-[var(--color-primary)]/10 flex items-center justify-center">
                                <svg class="h-16 w-16 text-[var(--color-secondary)]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-base font-semibold text-[var(--color-text-primary)]">{{ __('Designed for busy schedules') }}</h3>
                            <p class="text-sm text-[var(--color-text-muted)]">{{ __('Short lessons with strong outcomes, so progress fits around your work and life.') }}</p>
                        </div>
                    </div>
                </div>
            </section>


            @if ($showTestimonials)
            <section aria-label="{{ __('Testimonials') }}" class="space-y-8">
                <div class="space-y-3 text-center">
                    <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                        {{ __('Student results') }}
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-semibold text-[var(--color-text-primary)]">
                        {{ __('What people say after working together') }}
                    </h2>
                    <p class="text-sm sm:text-base text-[var(--color-text-muted)] max-w-2xl mx-auto">
                        {{ __('These are examples of the type of transformations and clarity you can expect when you commit to the work.') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=Sara&background=random" alt="{{ __('Sara avatar') }}" class="h-10 w-10 rounded-full ring-1 ring-[var(--color-secondary)]/20" loading="lazy">
                            <div>
                                <p class="text-xs font-semibold text-[var(--color-text-primary)]">{{ __('Sara') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Freelance designer') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-[var(--color-text-muted)]">
                            {{ __('"I went from avoiding sales calls to actually looking forward to them. The scripts and mindset shifts changed everything for me."') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=Omar&background=random" alt="{{ __('Omar avatar') }}" class="h-10 w-10 rounded-full ring-1 ring-[var(--color-secondary)]/20" loading="lazy">
                            <div>
                                <p class="text-xs font-semibold text-[var(--color-text-primary)]">{{ __('Omar') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Sales representative') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-[var(--color-text-muted)]">
                            {{ __('"After our interview prep, I received two offers in the same week. I finally knew how to talk about my experience clearly."') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=Laila&background=random" alt="{{ __('Laila avatar') }}" class="h-10 w-10 rounded-full ring-1 ring-[var(--color-secondary)]/20" loading="lazy">
                            <div>
                                <p class="text-xs font-semibold text-[var(--color-text-primary)]">{{ __('Laila') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Startup founder') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-[var(--color-text-muted)]">
                            {{ __('"The frameworks are simple but powerful. I used them with my team and we closed our biggest month so far."') }}
                        </p>
                    </div>
                </div>
            </section>
            @endif

            @if ($showContactForm === true)
            <section id="contact" aria-label="{{ __('Contact') }}" class="space-y-8">
                @if (session('status'))
                    <div class="rounded-lg border border-[var(--color-accent)]/20 bg-[var(--color-accent)]/10 px-4 py-2 text-sm text-[var(--color-accent)]">
                        {{ session('status') }}
                    </div>
                @endif
                @error('captcha')
                    <div class="rounded-lg border border-[var(--color-error)]/20 bg-[var(--color-error)]/10 px-4 py-2 text-sm text-[var(--color-error)]">
                        {{ $message }}
                    </div>
                @enderror
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <h2 class="text-2xl sm:text-3xl font-semibold text-[var(--color-text-primary)]">
                            {{ __('Get in touch') }}
                        </h2>
                        <p class="text-sm sm:text-base text-[var(--color-text-muted)]">
                            {{ __('Send a message and I will reply shortly.') }}
                        </p>
                    </div>
                    <form id="contactForm" method="POST" action="{{ route('contact.submit') }}" class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 space-y-4">
                        @csrf
                        <div>
                            <label for="contact_name" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Name') }}</label>
                            <input id="contact_name" name="name" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('name') }}" required>
                            @error('name')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Email') }}</label>
                            <input id="contact_email" name="email" type="email" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('email') }}" required>
                            @error('email')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="contact_message" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Message') }}</label>
                            <textarea id="contact_message" name="message" rows="5" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" required>{{ old('message') }}</textarea>
                            @error('message')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <input type="hidden" id="captcha_token" name="captcha_token" value="">
                        <div class="flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                {{ __('Send Message') }}
                            </button>
                        </div>
                    </form>
                </div>
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
            </section>
            @endif

            @if ($showFooterCta)
            <section aria-label="{{ __('Final call to action') }}">
                <div class="rounded-3xl bg-[var(--color-primary)] text-white px-6 sm:px-10 py-10 lg:py-12 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-2">
                        <h2 class="text-2xl sm:text-3xl font-semibold">
                            {{ __('Ready to take your next sales step?') }}
                        </h2>
                        <p class="text-sm sm:text-base text-white/80 max-w-xl">
                            {{ __('Start with one focused program, apply the frameworks, and watch your confidence and results grow with every conversation.') }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row flex-wrap items-center gap-3 w-full md:w-auto">
                        <a href="{{ route('courses.index') }}"
                           class="inline-flex justify-center items-center w-full sm:w-auto px-4 py-2 rounded-md bg-white text-sm font-semibold text-[var(--color-primary)] shadow-sm hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                            {{ __('Courses') }}
                        </a>
                    </div>
                </div>
            </section>
            @endif
        </div>
    </div>
</x-public-layout>
