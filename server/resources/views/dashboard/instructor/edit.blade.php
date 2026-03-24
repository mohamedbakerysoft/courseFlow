<x-app-layout>
    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 space-y-3">
            <x-breadcrumbs :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Instructor Profile')],
            ]" />
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">
                    {{ __('Portfolio Page') }}
                </h1>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 cf-status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.instructor_profile.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Profile Basics') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="page_label" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Page Label') }}
                        </label>
                        <input id="page_label" name="page_label" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('page_label', $pageLabel) }}" placeholder="{{ __('My profile') }}">
                    </div>
                    <div>
                        <label for="instructor_name" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Display Name') }}
                        </label>
                        <input id="instructor_name" name="instructor_name" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('instructor_name', $instructorName) }}">
                        @error('instructor_name')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="instructor_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Job Title') }}
                        </label>
                        <input id="instructor_title" name="instructor_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('instructor_title', $instructorTitle) }}">
                        @error('instructor_title')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="instructor_bio" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Short Bio') }}
                        </label>
                        <textarea id="instructor_bio" name="instructor_bio" rows="4" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('instructor_bio', $instructorBio) }}</textarea>
                        @error('instructor_bio')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="instructor_image" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Profile Image') }}
                        </label>
                        <input id="instructor_image" name="instructor_image" type="file" accept="image/*" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('instructor_image')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if (!empty($instructorImageUrl))
                            <div class="mt-3 flex items-center gap-3">
                                <img src="{{ $instructorImageUrl }}" alt="{{ $instructorName }}" class="w-20 h-20 rounded-full object-cover ring-4 ring-[var(--color-primary)]/10" onerror="this.onerror=null;this.src='{{ \App\Support\MediaAsset::avatarFallback($instructorName) }}';">
                                <div class="text-xs text-[var(--color-text-muted)]">
                                    {{ __('Current profile image preview') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Hero Content') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="hero_headline" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Hero Headline') }}
                        </label>
                        <input id="hero_headline" name="hero_headline" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('hero_headline', $heroHeadline) }}">
                        @error('hero_headline')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="hero_subheadline" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Hero Subheadline') }}
                        </label>
                        <textarea id="hero_subheadline" name="hero_subheadline" rows="4" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('hero_subheadline', $heroSubheadline) }}</textarea>
                        @error('hero_subheadline')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="best_for_text" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Best For Text') }}
                        </label>
                        <input id="best_for_text" name="best_for_text" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('best_for_text', $bestForText) }}">
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Catalog Section') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="catalog_label" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Catalog Label') }}
                        </label>
                        <input id="catalog_label" name="catalog_label" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('catalog_label', $catalogLabel) }}">
                    </div>
                    <div>
                        <label for="catalog_heading" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Catalog Heading') }}
                        </label>
                        <input id="catalog_heading" name="catalog_heading" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('catalog_heading', $catalogHeading) }}">
                    </div>
                    <div>
                        <label for="catalog_description" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Catalog Description') }}
                        </label>
                        <textarea id="catalog_description" name="catalog_description" rows="4" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('catalog_description', $catalogDescription) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="primary_cta_label" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Primary CTA Label') }}
                            </label>
                            <input id="primary_cta_label" name="primary_cta_label" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('primary_cta_label', $primaryCtaLabel) }}">
                        </div>
                        <div>
                            <label for="secondary_cta_label" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Secondary CTA Label') }}
                            </label>
                            <input id="secondary_cta_label" name="secondary_cta_label" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('secondary_cta_label', $secondaryCtaLabel) }}">
                        </div>
                    </div>
                    <div>
                        <label for="focus_areas" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Focus Areas') }}
                        </label>
                        <textarea id="focus_areas" name="focus_areas" rows="4" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('focus_areas', $focusAreas) }}</textarea>
                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ __('One item per line.') }}</p>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Trust Section') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="expectations_label" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Section Label') }}
                        </label>
                        <input id="expectations_label" name="expectations_label" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('expectations_label', $expectationsLabel) }}">
                    </div>
                    <div>
                        <label for="expectations_heading" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Section Heading') }}
                        </label>
                        <input id="expectations_heading" name="expectations_heading" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('expectations_heading', $expectationsHeading) }}">
                    </div>
                    <div>
                        <label for="expectations" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Expectation Points') }}
                        </label>
                        <textarea id="expectations" name="expectations" rows="5" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('expectations', $expectations) }}</textarea>
                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ __('One item per line.') }}</p>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Social') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="social_website" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Website') }}
                        </label>
                        <input id="social_website" name="social_website" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('social_website', $socialWebsite) }}" placeholder="https://example.com">
                    </div>
                    <div>
                        <label for="social_twitter" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Twitter') }}
                        </label>
                        <input id="social_twitter" name="social_twitter" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('social_twitter', $socialTwitter) }}" placeholder="https://twitter.com/username">
                        @error('social_twitter')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="social_instagram" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Instagram') }}
                        </label>
                        <input id="social_instagram" name="social_instagram" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('social_instagram', $socialInstagram) }}" placeholder="https://instagram.com/username">
                        @error('social_instagram')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="social_youtube" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('YouTube') }}
                        </label>
                        <input id="social_youtube" name="social_youtube" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('social_youtube', $socialYouTube) }}" placeholder="https://www.youtube.com/watch?v=M7lc1UVf-VE">
                        @error('social_youtube')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="social_linkedin" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('LinkedIn') }}
                        </label>
                        <input id="social_linkedin" name="social_linkedin" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('social_linkedin', $socialLinkedIn) }}" placeholder="https://www.linkedin.com/in/username">
                        @error('social_linkedin')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="social_facebook" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Facebook') }}
                        </label>
                        <input id="social_facebook" name="social_facebook" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('social_facebook', $socialFacebook) }}" placeholder="https://facebook.com/username">
                        @error('social_facebook')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="mt-8 flex items-center justify-between gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-[var(--color-secondary)]/30 bg-white text-sm font-medium text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Profile') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
