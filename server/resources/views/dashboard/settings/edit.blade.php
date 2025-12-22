<x-app-layout>
    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 space-y-3">
            <x-breadcrumbs :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Settings')],
            ]" />
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">
                    {{ __('Settings') }}
                </h1>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg border border-[var(--color-accent)]/20 bg-[var(--color-accent)]/10 px-4 py-2 text-sm text-[var(--color-accent)]">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('General Settings') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="default_language" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Default Language') }}
                        </label>
                        <select id="default_language" name="default_language" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            <option value="en" @selected($defaultLanguage === 'en')>English</option>
                            <option value="ar" @selected($defaultLanguage === 'ar')>العربية</option>
                        </select>
                        @error('default_language')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Site Logo') }}
                        </label>
                        <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[var(--color-text-primary)] border-[var(--color-secondary)]/30 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('logo')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($logoUrl)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-[var(--color-text-muted)] mb-1">{{ __('Current logo') }}</p>
                                <div class="inline-flex items-center justify-center rounded-xl border border-[var(--color-secondary)]/20 bg-white shadow-sm p-3">
                                    <img src="{{ $logoUrl }}" alt="Logo" class="h-16 w-16 object-contain">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Legal Pages') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="legal_terms_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Terms of Service (English)') }}
                        </label>
                        <textarea id="legal_terms_en" name="legal_terms_en" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_terms_en', $legalTermsEn) }}</textarea>
                        @error('legal_terms_en')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="legal_terms_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('شروط الخدمة (العربية)') }}
                        </label>
                        <textarea id="legal_terms_ar" name="legal_terms_ar" dir="rtl" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_terms_ar', $legalTermsAr) }}</textarea>
                        @error('legal_terms_ar')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="legal_privacy_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Privacy Policy (English)') }}
                        </label>
                        <textarea id="legal_privacy_en" name="legal_privacy_en" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_privacy_en', $legalPrivacyEn) }}</textarea>
                        @error('legal_privacy_en')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="legal_privacy_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('سياسة الخصوصية (العربية)') }}
                        </label>
                        <textarea id="legal_privacy_ar" name="legal_privacy_ar" dir="rtl" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_privacy_ar', $legalPrivacyAr) }}</textarea>
                        @error('legal_privacy_ar')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Payment Methods') }}
                </h2>
                <div class="space-y-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-[var(--color-text-primary)]">Stripe</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ __('Enable Stripe checkout buttons.') }}</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="payments_stripe_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($paymentsStripeEnabled)>
                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-[var(--color-text-primary)]">PayPal</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ __('Enable PayPal checkout buttons.') }}</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="payments_paypal_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($paymentsPaypalEnabled)>
                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div>
                        <label for="payments_manual_instructions" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Manual payment instructions') }}
                        </label>
                        <textarea id="payments_manual_instructions" name="payments_manual_instructions" rows="4" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('payments_manual_instructions', $paymentsManualInstructions) }}</textarea>
                        @error('payments_manual_instructions')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                            {{ __('These instructions will be shown to students choosing manual payments (bank transfer, cash, etc.).') }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Landing Page') }}
                </h2>
                <div class="space-y-5">
                    <div>
                        <label for="instructor_name" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Instructor Name') }}
                        </label>
                        <input id="instructor_name" name="instructor_name" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('instructor_name', $instructorName ?? '') }}">
                        @error('instructor_name')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Hero') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle visibility of the hero section.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_hero" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowHero)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Contact Form') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle simple contact form at the bottom.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_contact_form" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowContactForm ?? false)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show About Instructor') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle instructor bio block inside hero.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_about" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowAbout)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Courses Preview') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle featured courses grid.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_courses_preview" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowCoursesPreview)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Testimonials') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle testimonials section.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_testimonials" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowTestimonials)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Footer CTA') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle final call to action.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_footer_cta" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowFooterCta)>
                        </label>
                    </div>
                    <div>
                        <label for="landing_hero_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Hero title') }}
                        </label>
                        <input id="landing_hero_title" name="landing_hero_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('landing_hero_title', $landingHeroTitle) }}">
                        @error('landing_hero_title')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="landing_hero_title_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero title (EN)') }}
                            </label>
                            <input id="landing_hero_title_en" name="landing_hero_title_en" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_hero_title_en', $landingHeroTitleEn ?? '') }}">
                        </div>
                        <div>
                            <label for="landing_hero_title_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero title (AR)') }}
                            </label>
                            <input id="landing_hero_title_ar" name="landing_hero_title_ar" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_hero_title_ar', $landingHeroTitleAr ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <label for="landing_hero_subtitle" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Hero subtitle') }}
                        </label>
                        <input id="landing_hero_subtitle" name="landing_hero_subtitle" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('landing_hero_subtitle', $landingHeroSubtitle) }}">
                        @error('landing_hero_subtitle')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="landing_hero_subtitle_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero subtitle (EN)') }}
                            </label>
                            <input id="landing_hero_subtitle_en" name="landing_hero_subtitle_en" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_hero_subtitle_en', $landingHeroSubtitleEn ?? '') }}">
                        </div>
                        <div>
                            <label for="landing_hero_subtitle_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero subtitle (AR)') }}
                            </label>
                            <input id="landing_hero_subtitle_ar" name="landing_hero_subtitle_ar" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_hero_subtitle_ar', $landingHeroSubtitleAr ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero Image Display Mode') }}</span>
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_hero_image_mode" value="contain" {{ old('landing_hero_image_mode', $landingHeroImageMode ?? 'contain') === 'contain' ? 'checked' : '' }}>
                                <span class="text-sm text-[var(--color-text-primary)]">{{ __('Fit (Show Full Image)') }}</span>
                            </label>
                            <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_hero_image_mode" value="cover" {{ old('landing_hero_image_mode', $landingHeroImageMode ?? 'contain') === 'cover' ? 'checked' : '' }}>
                                <span class="text-sm text-[var(--color-text-primary)]">{{ __('Fill (Crop to Container)') }}</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="landing_hero_image_focus" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Hero Image Focus') }}
                        </label>
                        <select id="landing_hero_image_focus" name="landing_hero_image_focus" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            @php $focus = $landingHeroImageFocus ?? 'center'; @endphp
                            <option value="center" @selected($focus === 'center')>{{ __('Center') }}</option>
                            <option value="top" @selected($focus === 'top')>{{ __('Top') }}</option>
                            <option value="bottom" @selected($focus === 'bottom')>{{ __('Bottom') }}</option>
                            <option value="left" @selected($focus === 'left')>{{ __('Left') }}</option>
                            <option value="right" @selected($focus === 'right')>{{ __('Right') }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label for="landing_feature_1_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 1 title') }}
                            </label>
                            <input id="landing_feature_1_title" name="landing_feature_1_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_1_title', $landingFeature1Title) }}">
                            <label for="landing_feature_1_description" class="block text-xs font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 1 description') }}
                            </label>
                            <textarea id="landing_feature_1_description" name="landing_feature_1_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_1_description', $landingFeature1Description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="landing_feature_2_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 2 title') }}
                            </label>
                            <input id="landing_feature_2_title" name="landing_feature_2_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_2_title', $landingFeature2Title) }}">
                            <label for="landing_feature_2_description" class="block text-xs font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 2 description') }}
                            </label>
                            <textarea id="landing_feature_2_description" name="landing_feature_2_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_2_description', $landingFeature2Description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="landing_feature_3_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 3 title') }}
                            </label>
                            <input id="landing_feature_3_title" name="landing_feature_3_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_3_title', $landingFeature3Title) }}">
                            <label for="landing_feature_3_description" class="block text-xs font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 3 description') }}
                            </label>
                            <textarea id="landing_feature_3_description" name="landing_feature_3_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_3_description', $landingFeature3Description) }}</textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="social_twitter" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Twitter') }}
                            </label>
                            <input id="social_twitter" name="social_twitter" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_twitter', $socialTwitter ?? '') }}" placeholder="https://twitter.com/username">
                        </div>
                        <div>
                            <label for="social_instagram" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Instagram') }}
                            </label>
                            <input id="social_instagram" name="social_instagram" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_instagram', $socialInstagram ?? '') }}" placeholder="https://instagram.com/username">
                        </div>
                        <div>
                            <label for="social_youtube" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('YouTube') }}
                            </label>
                            <input id="social_youtube" name="social_youtube" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_youtube', $socialYouTube ?? '') }}" placeholder="https://youtube.com/@channel">
                        </div>
                        <div>
                            <label for="social_linkedin" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('LinkedIn') }}
                            </label>
                            <input id="social_linkedin" name="social_linkedin" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_linkedin', $socialLinkedIn ?? '') }}" placeholder="https://www.linkedin.com/in/username">
                        </div>
                    </div>
                    <div>
                        <label for="landing_instructor_image" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Instructor hero image') }}
                        </label>
                        <input id="landing_instructor_image" name="landing_instructor_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[var(--color-text-primary)] border-[var(--color-secondary)]/30 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('landing_instructor_image')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($landingInstructorImageUrl)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-[var(--color-text-muted)] mb-1">{{ __('Current hero image') }}</p>
                                <div class="overflow-hidden rounded-xl ring-1 ring-[var(--color-secondary)]/20 shadow-sm">
                                    <img src="{{ $landingInstructorImageUrl }}" alt="Instructor hero" class="w-48 h-48 object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <div class="mt-8 flex items-center justify-between gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-[var(--color-secondary)]/30 bg-white text-sm font-medium text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Settings') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
