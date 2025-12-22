<x-guest-layout>
    <div x-data="{ demoLogin(email, password) { $refs.email.value = email; $refs.password.value = password; $refs.form.submit() } }">
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-[var(--color-text-primary)]">
            {{ __('Login') }}
        </h1>
        <p class="text-sm text-[var(--color-text-muted)]">
            {{ __('Access your dashboard and manage courses.') }}
        </p>
    </div>
    @if(config('demo.enabled'))
        <div class="mb-4">
            <div class="bg-white rounded-lg shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-4 flex flex-col sm:flex-row items-center gap-3">
                <a href="{{ route('demo.login', ['who' => 'admin']) }}" data-test="demo-admin" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold hover:bg-[var(--color-primary-hover)]">
                    {{ __('Login as Admin') }}
                </a>
                <a href="{{ route('demo.login', ['who' => 'student']) }}" data-test="demo-student" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-secondary)] text-white text-sm font-semibold hover:opacity-90">
                    {{ __('Login as Student') }}
                </a>
            </div>
            <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                {{ __('Demo credentials are pre-filled for quick exploration.') }}
            </p>
        </div>
    @endif
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-ref="form">
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
    @if (($googleLoginEnabled ?? false) === true)
        <div class="mt-6">
            <a href="{{ route('auth.google.redirect') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-secondary)] text-white text-sm font-semibold hover:opacity-90">
                {{ __('Continue with Google') }}
            </a>
        </div>
    @endif
    </div>
</x-guest-layout>
