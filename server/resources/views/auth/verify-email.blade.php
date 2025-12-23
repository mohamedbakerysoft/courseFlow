<x-guest-layout>
    <div class="mb-6">
        <span class="cf-kicker">{{ __('Email verification') }}</span>
        <h1 class="mt-4 text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Verify your email address') }}</h1>
        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">
            {{ __('Before getting started, verify your email address by clicking the link we just sent. If it did not arrive, we can send another one right away.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 cf-status-message">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
