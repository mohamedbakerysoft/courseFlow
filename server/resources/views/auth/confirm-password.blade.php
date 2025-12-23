<x-guest-layout>
    <div class="mb-6">
        <span class="cf-kicker">{{ __('Security check') }}</span>
        <h1 class="mt-4 text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Confirm your password') }}</h1>
        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">
            {{ __('This is a secure area of the application. Confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end pt-1">
            <x-primary-button class="w-full justify-center sm:w-auto">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
