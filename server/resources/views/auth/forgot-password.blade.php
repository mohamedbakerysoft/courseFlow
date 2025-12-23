<x-guest-layout>
    <div class="mb-6">
        <span class="cf-kicker">{{ __('Password reset') }}</span>
        <h1 class="mt-4 text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Reset your password') }}</h1>
        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">
            {{ __('Enter your email address and we will send a secure reset link so you can get back into your account quickly.') }}
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end pt-1">
            <x-primary-button class="w-full justify-center sm:w-auto">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
