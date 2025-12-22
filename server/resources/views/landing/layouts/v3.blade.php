<x-public-layout :title="$heroTitle" :metaDescription="$heroSubtitle">
    <div class="bg-white">
        <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-10 space-y-16 py-10 lg:py-20">
            @if ($showHero)
            <section id="hero" class="text-center space-y-6">
                <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-[var(--color-text-primary)]">
                    {{ $heroTitle }}
                </h1>
                <p class="text-base sm:text-lg text-[var(--color-text-muted)] max-w-2xl mx-auto">
                    {{ $heroSubtitle }}
                </p>
                <div class="mx-auto max-w-xl">
                    <div class="rounded-2xl shadow-lg ring-1 ring-[var(--color-secondary)]/10 bg-white p-2">
                        <img
                            src="{{ $heroImageUrl ?? asset('images/demo/IMG_1702.JPG') }}"
                            alt="{{ __('Hero Image') }}"
                            style="object-position: {{ $heroImageFocus ?? 'center' }}; aspect-ratio: {{ $heroImageRatio ?? '16/9' }}"
                            class="w-full h-auto {{ $heroImageMode === 'contain' ? 'object-contain' : 'object-cover' }} rounded-xl"
                            loading="lazy"
                        >
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex justify-center items-center px-5 py-2.5 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                        {{ app()->getLocale() === 'ar' ? 'احصل على وصول فوري' : 'Get instant access' }}
                    </a>
                </div>
                <p class="text-xs text-[var(--color-text-muted)] text-center">
                    {{ app()->getLocale() === 'ar' ? 'لا اشتراك · دفع لمرة واحدة' : 'No subscription · One‑time payment' }}
                    ·
                    {{ app()->getLocale() === 'ar' ? 'وصول فوري · دفع آمن' : 'Instant access · Secure checkout' }}
                </p>
            </section>
            @endif
            <x-public.trust-bar />

            @if ($showCoursesPreview)
            <section aria-label="{{ __('Featured courses') }}" class="space-y-8">
                <div class="space-y-2 text-center">
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
                <x-public.trust-bar />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($featuredCourses as $course)
                        <x-course.card :course="$course" />
                    @empty
                        <p class="text-sm text-[var(--color-text-muted)] text-center">{{ __('No courses available yet') }}</p>
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
                <div class="max-w-3xl mx-auto">
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
