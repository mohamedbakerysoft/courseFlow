<x-guest-layout>
    <div class="mb-4">
        <h1 class="text-xl font-semibold text-[var(--color-text-primary)]">
            {{ __('Register') }}
        </h1>
        <p class="text-sm text-[var(--color-text-muted)]">
            {{ __('Create your account to manage courses and lessons.') }}
        </p>
    </div>
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)]">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        <input type="hidden" id="captcha_token" name="captcha_token" value="">
        @php $siteKey = config('services.recaptcha.site_key'); @endphp
        @if (!empty($siteKey))
            <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var form = document.getElementById('registerForm');
                    if (!form) return;
                    form.addEventListener('submit', function (e) {
                        if (typeof grecaptcha === 'undefined') return;
                        e.preventDefault();
                        grecaptcha.ready(function () {
                            grecaptcha.execute('{{ $siteKey }}', {action: 'register'}).then(function (token) {
                                var input = document.getElementById('captcha_token');
                                if (input) input.value = token;
                                form.submit();
                            });
                        });
                    }, { passive: false });
                });
            </script>
        @endif
    </form>
    @if (($googleLoginEnabled ?? false) === true)
        <div class="mt-6">
            <a href="{{ route('auth.google.redirect') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-secondary)] text-white text-sm font-semibold hover:opacity-90">
                {{ __('Continue with Google') }}
            </a>
        </div>
    @endif
</x-guest-layout>
