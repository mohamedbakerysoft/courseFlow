<x-app-layout>
    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 space-y-3">
            <x-breadcrumbs :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Instructor Profile')],
            ]" />
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">
                    {{ __('Instructor Profile') }}
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

        <form method="POST" action="{{ route('dashboard.instructor_profile.update') }}" class="space-y-8">
            @csrf

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Profile') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="instructor_name" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Instructor Name') }}
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
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Hero') }}
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
                        <input id="hero_subheadline" name="hero_subheadline" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('hero_subheadline', $heroSubheadline) }}">
                        @error('hero_subheadline')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Hero Image Display Mode') }}
                        </span>
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="hero_image_mode" value="contain" {{ old('hero_image_mode', $heroImageMode) === 'contain' ? 'checked' : '' }}>
                                <span class="text-sm text-[var(--color-text-primary)]">{{ __('Fit (Show Full Image)') }}</span>
                            </label>
                            <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="hero_image_mode" value="cover" {{ old('hero_image_mode', $heroImageMode) === 'cover' ? 'checked' : '' }}>
                                <span class="text-sm text-[var(--color-text-primary)]">{{ __('Fill (Crop to Container)') }}</span>
                            </label>
                        </div>
                        @error('hero_image_mode')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Social') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
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
                               value="{{ old('social_youtube', $socialYouTube) }}" placeholder="https://youtube.com/@channel">
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
