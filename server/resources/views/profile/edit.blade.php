<x-app-layout>
    <x-slot name="header">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-white/55">{{ __('Account settings') }}</p>
            <h2 class="mt-3 text-3xl font-bold tracking-[-0.04em] text-white sm:text-4xl">{{ __('Profile and security in a cleaner settings workspace') }}</h2>
            <p class="mt-3 text-sm leading-7 text-white/72">{{ __('Personal details, password security, and account controls now sit inside the same upgraded product language.') }}</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="cf-panel px-6 py-6 sm:px-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="cf-panel px-6 py-6 sm:px-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="cf-panel px-6 py-6 sm:px-8">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
