<x-guest-layout>
    <div x-data="{ demoLogin(email, password) { $refs.email.value = email; $refs.password.value = password; $refs.form.submit() } }">
        <div class="mb-6">
            <span class="cf-kicker">{{ __('Login') }}</span>
            <h1 class="mt-4 text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Welcome back') }}</h1>
            <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Access your dashboard, manage your courses, and continue the student journey through a cleaner account experience.') }}</p>
        </div>

        @if(config('demo.enabled'))
            <div class="mb-6 cf-panel-soft px-4 py-4">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('demo.login', ['who' => 'admin']) }}" data-test="demo-admin" class="cf-button-primary">{{ __('Login as Admin') }}</a>
                    <a href="{{ route('demo.login', ['who' => 'student']) }}" data-test="demo-student" class="cf-button-secondary">{{ __('Login as Student') }}</a>
                </div>
                <p class="mt-3 text-xs text-[var(--color-text-muted)]">{{ __('Demo mode keeps one-click access available for visitors exploring the admin and student flows.') }}</p>
            </div>
        @endif

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" x-ref="form" class="space-y-5">
            @csrf
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input x-ref="email" id="email" class="mt-2" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-[var(--color-error)]" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input x-ref="password" id="password" class="mt-2" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-[var(--color-error)]" />
            </div>

            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] shadow-sm focus:ring-[var(--color-primary)]" name="remember">
                <span class="text-sm text-[var(--color-text-muted)]">{{ __('Remember me') }}</span>
            </label>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-[var(--color-text-muted)] underline-offset-4 hover:text-[var(--color-text-primary)] hover:underline" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="w-full justify-center sm:w-auto">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>

        @if (($googleLoginEnabled ?? false) === true)
            <div class="mt-6">
                <a href="{{ route('auth.google.redirect') }}" class="cf-button-secondary w-full">{{ __('Continue with Google') }}</a>
            </div>
        @endif
    </div>
</x-guest-layout>
