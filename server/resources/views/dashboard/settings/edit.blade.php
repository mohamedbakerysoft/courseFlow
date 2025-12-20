<x-app-layout>
    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 space-y-3">
            <x-breadcrumbs :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Settings')],
            ]" />
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">
                    {{ __('Settings') }}
                </h1>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <section class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('General Settings') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="default_language" class="block text-sm font-medium text-gray-700">
                            {{ __('Default Language') }}
                        </label>
                        <select id="default_language" name="default_language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            <option value="en" @selected($defaultLanguage === 'en')>English</option>
                            <option value="ar" @selected($defaultLanguage === 'ar')>العربية</option>
                        </select>
                        @error('default_language')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700">
                            {{ __('Site Logo') }}
                        </label>
                        <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-900 border-gray-300 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('logo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($logoUrl)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-gray-500 mb-1">{{ __('Current logo') }}</p>
                                <div class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white shadow-sm p-3">
                                    <img src="{{ $logoUrl }}" alt="Logo" class="h-16 w-16 object-contain">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Payment Methods') }}
                </h2>
                <div class="space-y-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Stripe</p>
                            <p class="text-xs text-gray-500">{{ __('Enable Stripe checkout buttons.') }}</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="payments_stripe_enabled" value="1" class="rounded border-gray-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($paymentsStripeEnabled)>
                            <span class="text-sm text-gray-700">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-800">PayPal</p>
                            <p class="text-xs text-gray-500">{{ __('Enable PayPal checkout buttons.') }}</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="payments_paypal_enabled" value="1" class="rounded border-gray-300 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($paymentsPaypalEnabled)>
                            <span class="text-sm text-gray-700">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div>
                        <label for="payments_manual_instructions" class="block text-sm font-medium text-gray-700">
                            {{ __('Manual payment instructions') }}
                        </label>
                        <textarea id="payments_manual_instructions" name="payments_manual_instructions" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('payments_manual_instructions', $paymentsManualInstructions) }}</textarea>
                        @error('payments_manual_instructions')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            {{ __('These instructions will be shown to students choosing manual payments (bank transfer, cash, etc.).') }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Landing Page') }}
                </h2>
                <div class="space-y-5">
                    <div>
                        <label for="landing_hero_title" class="block text-sm font-medium text-gray-700">
                            {{ __('Hero title') }}
                        </label>
                        <input id="landing_hero_title" name="landing_hero_title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('landing_hero_title', $landingHeroTitle) }}">
                        @error('landing_hero_title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="landing_hero_subtitle" class="block text-sm font-medium text-gray-700">
                            {{ __('Hero subtitle') }}
                        </label>
                        <input id="landing_hero_subtitle" name="landing_hero_subtitle" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('landing_hero_subtitle', $landingHeroSubtitle) }}">
                        @error('landing_hero_subtitle')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label for="landing_feature_1_title" class="block text-sm font-medium text-gray-700">
                                {{ __('Feature 1 title') }}
                            </label>
                            <input id="landing_feature_1_title" name="landing_feature_1_title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_1_title', $landingFeature1Title) }}">
                            <label for="landing_feature_1_description" class="block text-xs font-medium text-gray-500">
                                {{ __('Feature 1 description') }}
                            </label>
                            <textarea id="landing_feature_1_description" name="landing_feature_1_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_1_description', $landingFeature1Description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="landing_feature_2_title" class="block text-sm font-medium text-gray-700">
                                {{ __('Feature 2 title') }}
                            </label>
                            <input id="landing_feature_2_title" name="landing_feature_2_title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_2_title', $landingFeature2Title) }}">
                            <label for="landing_feature_2_description" class="block text-xs font-medium text-gray-500">
                                {{ __('Feature 2 description') }}
                            </label>
                            <textarea id="landing_feature_2_description" name="landing_feature_2_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_2_description', $landingFeature2Description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="landing_feature_3_title" class="block text-sm font-medium text-gray-700">
                                {{ __('Feature 3 title') }}
                            </label>
                            <input id="landing_feature_3_title" name="landing_feature_3_title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_3_title', $landingFeature3Title) }}">
                            <label for="landing_feature_3_description" class="block text-xs font-medium text-gray-500">
                                {{ __('Feature 3 description') }}
                            </label>
                            <textarea id="landing_feature_3_description" name="landing_feature_3_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_3_description', $landingFeature3Description) }}</textarea>
                        </div>
                    </div>
                    <div>
                        <label for="landing_instructor_image" class="block text-sm font-medium text-gray-700">
                            {{ __('Instructor hero image') }}
                        </label>
                        <input id="landing_instructor_image" name="landing_instructor_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-900 border-gray-300 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('landing_instructor_image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($landingInstructorImageUrl)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-gray-500 mb-1">{{ __('Current hero image') }}</p>
                                <div class="overflow-hidden rounded-xl ring-1 ring-gray-200 shadow-sm">
                                    <img src="{{ $landingInstructorImageUrl }}" alt="Instructor hero" class="w-48 h-48 object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <div class="mt-8 flex items-center justify-between gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Settings') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
