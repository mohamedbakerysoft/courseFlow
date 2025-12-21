<x-guest-layout>
    <div x-data="{ fill(email, password) { $refs.email.value = email; $refs.password.value = password } }">
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-[var(--color-text-primary)]">
            {{ __('Login') }}
        </h1>
        <p class="text-sm text-[var(--color-text-muted)]">
            {{ __('Access your dashboard and manage courses.') }}
        </p>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input x-ref="email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input x-ref="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] shadow-sm focus:ring-[var(--color-primary)]" name="remember">
                <span class="ms-2 text-sm text-[var(--color-text-muted)]">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)]">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    @if(config('demo.enabled'))
        <div class="mt-6 bg-white border border-[var(--color-secondary)]/10 rounded-lg p-4">
            <p class="text-xs font-semibold text-[var(--color-text-muted)] mb-3">{{ __('Demo Fill') }}</p>
            <div class="flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--color-text-muted)]">Admin — {{ config('demo.admin_email') }}</span>
                    <x-secondary-button type="button" x-on:click="fill('{{ config('demo.admin_email') }}','password')">
                        {{ __('Fill') }}
                    </x-secondary-button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--color-text-muted)]">Instructor — instructor@demo.com</span>
                    <x-secondary-button type="button" x-on:click="fill('instructor@demo.com','password')">
                        {{ __('Fill') }}
                    </x-secondary-button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[var(--color-text-muted)]">Student — student@demo.com</span>
                    <x-secondary-button type="button" x-on:click="fill('student@demo.com','password')">
                        {{ __('Fill') }}
                    </x-secondary-button>
                </div>
            </div>
        </div>
    @endif
    </div>
</x-guest-layout>
