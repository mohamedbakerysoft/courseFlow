<x-guest-layout>
    <div class="mb-6">
        <span class="cf-kicker">{{ __('Register') }}</span>
        <h1 class="mt-4 text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Create your account') }}</h1>
        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Set up your account to manage courses, enroll students, and move through the platform with a cleaner premium interface.') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-2" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-2" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="mt-2" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[var(--color-error)]" />
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a class="text-sm text-[var(--color-text-muted)] underline-offset-4 hover:text-[var(--color-text-primary)] hover:underline" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="w-full justify-center sm:w-auto">
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
            <a href="{{ route('auth.google.redirect') }}" class="cf-button-secondary w-full">{{ __('Continue with Google') }}</a>
        </div>
    @endif
</x-guest-layout>
