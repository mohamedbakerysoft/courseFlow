<x-public-layout :title="$heroTitle" :metaDescription="$heroSubtitle">
    <div class="bg-white">
        <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-10 space-y-20 lg:space-y-24 py-10 lg:py-16">
            @if ($showHero)
            <header id="hero" class="relative min-h-[75vh]">
                <div class="relative w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-10 lg:gap-14">
                        <div class="order-2 lg:order-1 space-y-6">
                            <div class="space-y-2">
                                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-[var(--color-text-primary)]">
                                    {{ $heroTitle }}
                                </h1>
                                <p class="text-base sm:text-lg text-[var(--color-text-muted)]">
                                    {{ $heroSubtitle }}
                                </p>
                            </div>
                            @if (!empty($instructorName) && $showAboutInstructor)
                                <div class="rounded-xl ring-1 ring-[var(--color-secondary)]/10 bg-white p-4 flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-[var(--color-primary)]/10"></div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $instructorName }}</p>
                                        @if (!empty($instructorTitle))
                                            <p class="text-xs text-[var(--color-text-muted)]">{{ $instructorTitle }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-3">
                                <a href="{{ route('courses.index') }}"
                                   class="inline-flex justify-center items-center w-full sm:w-auto px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                    {{ app()->getLocale() === 'ar' ? 'احصل على وصول فوري' : 'Get instant access' }}
                                </a>
                                <p class="text-xs text-[var(--color-text-muted)]">
                                    {{ app()->getLocale() === 'ar' ? 'لا اشتراك · دفع لمرة واحدة' : 'No subscription · One‑time payment' }}
                                    ·
                                    {{ app()->getLocale() === 'ar' ? 'وصول فوري · دفع آمن' : 'Instant access · Secure checkout' }}
                                </p>
                            </div>
                        </div>
                        <div class="order-1 lg:order-2 flex justify-center lg:justify-end">
                            <div class="w-full max-w-xl lg:max-w-2xl transition-all duration-700 ease-out">
                                <div class="rounded-3xl shadow-lg ring-1 ring-[var(--color-primary)]/20 bg-white p-3">
                                    <img
                                        src="{{ $heroImageUrl ?? asset('images/demo/IMG_1701.PNG') }}"
                                        alt="{{ __('Hero Image') }}"
                                        style="object-position: {{ $heroImageFocus ?? 'center' }}; aspect-ratio: {{ $heroImageRatio ?? '16/9' }}"
                                        class="w-full h-auto transition-transform duration-700 ease-out {{ $heroImageMode === 'contain' ? 'object-contain' : 'object-cover' }} rounded-2xl"
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
                <x-public.trust-bar />
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($featuredCourses as $course)
                        <x-course.card :course="$course" />
                    @empty
                        <p class="text-sm text-[var(--color-text-muted)]">{{ __('No courses available yet') }}</p>
                    @endforelse
                </div>
            </section>
            @endif
            <x-public.social-proof />

            @if ($showContactForm === true)
            <section aria-label="{{ __('Contact') }}" class="space-y-8">
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
        </div>
    </div>
</x-public-layout>
