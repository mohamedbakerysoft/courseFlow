<x-app-layout>
    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ tab: (window.location.hash ? window.location.hash.substring(1) : 'general') }">
        <div class="mb-6 space-y-3">
            <x-breadcrumbs :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Settings')],
            ]" />
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">
                    {{ __('Settings') }}
                </h1>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
        <x-public.demo-notice />

        @if (session('status'))
            <div class="mb-4 rounded-lg border border-[var(--color-accent)]/20 bg-[var(--color-accent)]/10 px-4 py-2 text-sm text-[var(--color-accent)]">
                {{ session('status') }}
            </div>
        @endif

        <div class="border-b border-[var(--color-secondary)]/10">
            <nav class="-mb-[1px] flex flex-wrap gap-6" aria-label="{{ __('Settings tabs') }}">
                <a href="#general" @click.prevent="tab = 'general'; location.hash = 'general'"
                   class="px-1 py-4 text-sm font-medium border-b-2"
                   :class="tab === 'general' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:border-[var(--color-secondary)]/30'">
                    {{ __('General') }}
                </a>
                <a href="#payments" @click.prevent="tab = 'payments'; location.hash = 'payments'"
                   class="px-1 py-4 text-sm font-medium border-b-2"
                   :class="tab === 'payments' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:border-[var(--color-secondary)]/30'">
                    {{ __('Payments') }}
                </a>
                <a href="#authentication" @click.prevent="tab = 'authentication'; location.hash = 'authentication'"
                   class="px-1 py-4 text-sm font-medium border-b-2"
                   :class="tab === 'authentication' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:border-[var(--color-secondary)]/30'">
                    {{ __('Authentication') }}
                </a>
                <a href="#security" @click.prevent="tab = 'security'; location.hash = 'security'"
                   class="px-1 py-4 text-sm font-medium border-b-2"
                   :class="tab === 'security' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:border-[var(--color-secondary)]/30'">
                    {{ __('Security') }}
                </a>
                <a href="#notifications" @click.prevent="tab = 'notifications'; location.hash = 'notifications'"
                   class="px-1 py-4 text-sm font-medium border-b-2"
                   :class="tab === 'notifications' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:border-[var(--color-secondary)]/30'">
                    {{ __('Notifications') }}
                </a>
                <a href="#landing" @click.prevent="tab = 'landing'; location.hash = 'landing'"
                   class="px-1 py-4 text-sm font-medium border-b-2"
                   :class="tab === 'landing' ? 'border-[var(--color-primary)] text-[var(--color-primary)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] hover:border-[var(--color-secondary)]/30'">
                    {{ __('Landing') }}
                </a>
            </nav>
        </div>

        <form x-cloak x-show="tab === 'general'" method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data" class="space-y-8 mt-6">
            @csrf
            <input type="hidden" name="settings_group" value="general">
            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('General Settings') }}</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="default_language" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Default Language') }}</label>
                        <select id="default_language" name="default_language" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            <option value="en" @selected($defaultLanguage === 'en')>English</option>
                            <option value="ar" @selected($defaultLanguage === 'ar')>العربية</option>
                        </select>
                        @error('default_language')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="default_theme" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Default Theme') }}</label>
                        <select id="default_theme" name="default_theme" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            <option value="system" @selected(($defaultTheme ?? 'system') === 'system')>{{ __('System') }}</option>
                            <option value="light" @selected(($defaultTheme ?? 'system') === 'light')>{{ __('Light') }}</option>
                            <option value="dark" @selected(($defaultTheme ?? 'system') === 'dark')>{{ __('Dark') }}</option>
                        </select>
                        @error('default_theme')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="logo" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Site Logo') }}</label>
                        <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[var(--color-text-primary)] border-[var(--color-secondary)]/30 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('logo')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($logoUrl)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-[var(--color-text-muted)] mb-1">{{ __('Current logo') }}</p>
                                <div class="inline-flex items-center justify-center rounded-xl border border-[var(--color-secondary)]/20 bg-white shadow-sm p-3">
                                    <img src="{{ $logoUrl }}" alt="Logo" class="h-16 w-16 object-contain">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
            <div class="mt-4 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save General Settings') }}
                </button>
            </div>
        </form>

        <form x-cloak x-show="tab === 'payments'" method="POST" action="{{ route('dashboard.settings.update') }}" class="space-y-8 mt-6">
            @csrf
            <input type="hidden" name="settings_group" value="payments">
            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Payments') }}</h2>
                <div class="space-y-5">
                    <div class="rounded-md border border-[var(--color-secondary)]/20 bg-white p-3">
                        <p class="text-xs text-[var(--color-text-muted)]">
                            {{ app()->getLocale() === 'ar' ? 'شراء لمرة واحدة · وصول مدى الحياة · بدون رسوم شهرية' : 'One‑time purchase. Lifetime access. No monthly fees.' }}
                        </p>
                    </div>
                    <div class="rounded-md border border-[var(--color-secondary)]/20 p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Stripe Configuration') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('These keys are provided by Stripe Dashboard') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-{{ $stripeStatusVariant }}/20 bg-{{ $stripeStatusVariant }}/10 text-{{ $stripeStatusVariant }} text-xs">
                                {{ $stripeStatusLabel }}
                            </span>
                            @if (! empty($paymentsStripeEnabled))
                                @if (! empty($stripeWebhookSecretExists))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-[var(--color-primary)]/20 bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-xs">{{ __('Webhook ready') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-[var(--color-error)]/20 bg-[var(--color-error)]/10 text-[var(--color-error)] text-xs">{{ __('Webhook not configured') }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                                <div>
                                    <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Enable Stripe payments') }}</p>
                                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle Stripe checkout throughout the site.') }}</p>
                                </div>
                                <input type="checkbox" name="payments_stripe_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($paymentsStripeEnabled)>
                            </label>
                            <div>
                                <label for="stripe_mode" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Mode') }}</label>
                                <select id="stripe_mode" name="stripe_mode" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                    <option value="test" @selected(old('stripe_mode', $stripeMode) === 'test')>{{ __('Test') }}</option>
                                    <option value="live" @selected(old('stripe_mode', $stripeMode) === 'live')>{{ __('Live') }}</option>
                                </select>
                                <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ __('Switch between Test mode and Live mode in Stripe') }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="stripe_publishable_key" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Stripe Publishable Key') }}</label>
                                @if (! empty($stripePublishableKeyMasked))
                                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Current') }}: <code class="px-1 py-0.5 rounded bg-white border border-[var(--color-secondary)]/30">{{ $stripePublishableKeyMasked }}</code></p>
                                @endif
                                <input id="stripe_publishable_key" name="stripe_publishable_key" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('stripe_publishable_key', $stripePublishableKey) }}" placeholder="pk_test_...">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="stripe_secret_key" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Stripe Secret Key') }}</label>
                                <input id="stripe_secret_key" name="stripe_secret_key" type="password" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder="sk_test_...">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="stripe_webhook_endpoint" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Stripe Webhook Endpoint') }}</label>
                                <div x-data="{ copied: false }" class="mt-1 relative">
                                    <input id="stripe_webhook_endpoint" type="text" readonly class="block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm pr-20 focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ url('/webhooks/stripe') }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center gap-2 pr-3">
                                        <button type="button" class="px-3 py-1 rounded-md border border-[var(--color-secondary)]/30 bg-white text-xs text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]" aria-label="{{ __('Copy') }}"
                                            @click="navigator.clipboard.writeText(document.getElementById('stripe_webhook_endpoint').value); copied = true">
                                            {{ __('Copy') }}
                                        </button>
                                        <span x-show="copied" class="inline-flex items-center px-2 py-0.5 rounded-full border border-[var(--color-accent)]/20 bg-[var(--color-accent)]/10 text-[var(--color-accent)] text-xs">{{ __('Copied') }}</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ __('Add this URL to Stripe → Developers → Webhooks') }}</p>
                                <div class="mt-2 text-xs text-[var(--color-text-muted)]">
                                    <p class="font-medium">{{ __('Required events:') }}</p>
                                    <ul class="list-disc ml-4 space-y-0.5">
                                        <li><code>checkout.session.completed</code></li>
                                        <li><code>payment_intent.succeeded</code></li>
                                    </ul>
                                </div>
                                <div class="mt-3 rounded-md border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 p-3 space-y-2">
                                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Note: Stripe Dashboard cannot reach local domains directly. Use Stripe CLI to forward webhooks to your local Sail domain.') }}</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-[var(--color-text-primary)] font-medium">{{ __('Stripe CLI command') }}:</span>
                                        <code class="text-xs px-2 py-1 rounded bg-white border border-[var(--color-secondary)]/30 text-[var(--color-text-primary)]">stripe listen --forward-to {{ url('/webhooks/stripe') }}</code>
                                    </div>
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="stripe_webhook_secret" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Stripe Webhook Secret') }}</label>
                                <input id="stripe_webhook_secret" name="stripe_webhook_secret" type="password" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder="whsec_..." value="{{ ! empty($stripeWebhookSecretExists) ? '••••••••' : '' }}">
                                <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ __('Store your Stripe signing secret. For local dev, copy it from Stripe CLI output after running stripe listen. The value is masked after save.') }}</p>
                            </div>
                        </div>
                        <div class="rounded-md border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 p-3">
                            <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Where do I get these keys?') }}</p>
                            <ul class="mt-2 text-xs text-[var(--color-text-muted)] space-y-1">
                                <li>{{ __('Go to') }} <a href="https://dashboard.stripe.com" target="_blank" rel="noopener" class="underline text-[var(--color-primary)]">https://dashboard.stripe.com</a></li>
                                <li>{{ __('Switch to Test Mode (top right)') }}</li>
                                <li>{{ __('Open Developers → API keys') }}</li>
                                <li>{{ __('Copy: Publishable key (starts with pk_) and Secret key (starts with sk_)') }}</li>
                                <li>{{ __('Test keys are safe for demos and local testing') }}</li>
                            </ul>
                            @error('stripe')
                                <p class="text-xs text-[var(--color-error)] mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="rounded-md border border-[var(--color-secondary)]/20 bg-white p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('PayPal Configuration') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('These credentials are provided in PayPal Developer Dashboard') }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-{{ $paypalStatusVariant }}/20 bg-{{ $paypalStatusVariant }}/10 text-{{ $paypalStatusVariant }} text-xs">
                                {{ $paypalStatusLabel }}{{ $paypalMode ? ' ('.ucfirst($paypalMode).')' : '' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                                <div>
                                    <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Enable PayPal payments') }}</p>
                                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle PayPal checkout throughout the site.') }}</p>
                                </div>
                                <input type="checkbox" name="payments_paypal_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($paymentsPaypalEnabled)>
                            </label>
                            <div>
                                <label for="paypal_mode" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Mode') }}</label>
                                <select id="paypal_mode" name="paypal_mode" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                    <option value="sandbox" @selected(old('paypal_mode', $paypalMode) === 'sandbox')>{{ __('Sandbox') }}</option>
                                    <option value="live" @selected(old('paypal_mode', $paypalMode) === 'live')>{{ __('Live') }}</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="paypal_client_id" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('PayPal Client ID') }}</label>
                                @if (! empty($paypalClientIdMasked))
                                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Current') }}: <code class="px-1 py-0.5 rounded bg-white border border-[var(--color-secondary)]/30">{{ $paypalClientIdMasked }}</code></p>
                                @endif
                                <input id="paypal_client_id" name="paypal_client_id" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('paypal_client_id', $paypalClientId) }}" placeholder="A...">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="paypal_client_secret" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('PayPal Client Secret') }}</label>
                                <input id="paypal_client_secret" name="paypal_client_secret" type="password" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder="E...">
                            </div>
                        </div>
                        <div class="rounded-md border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 p-3">
                            <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Where do I get these PayPal credentials?') }}</p>
                            <ul class="mt-2 text-xs text-[var(--color-text-muted)] space-y-1">
                                <li>{{ __('Go to') }} <a href="https://developer.paypal.com" target="_blank" rel="noopener" class="underline text-[var(--color-primary)]">https://developer.paypal.com</a></li>
                                <li>{{ __('Log in with your PayPal account') }}</li>
                                <li>{{ __('Go to Dashboard → My Apps & Credentials') }}</li>
                                <li>{{ __('Under Sandbox or Live: Copy Client ID and Secret') }}</li>
                                <li>{{ __('Sandbox = testing/demo, Live = real payments') }}</li>
                                <li>{{ __('Sandbox credentials are safe for demos and local testing') }}</li>
                            </ul>
                            @error('paypal')
                                <p class="text-xs text-[var(--color-error)] mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="payments_manual_instructions" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Manual payment instructions') }}</label>
                        <textarea id="payments_manual_instructions" name="payments_manual_instructions" rows="4" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('payments_manual_instructions', $paymentsManualInstructions) }}</textarea>
                        @error('payments_manual_instructions')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-[var(--color-text-muted)]">{{ __('These instructions will be shown to students choosing manual payments (bank transfer, cash, etc.).') }}</p>
                    </div>
                </div>
            </section>
            <div class="mt-4 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Payments Settings') }}
                </button>
            </div>
        </form>

        <form x-cloak x-show="tab === 'authentication'" method="POST" action="{{ route('dashboard.settings.update') }}" class="space-y-8 mt-6">
            @csrf
            <input type="hidden" name="settings_group" value="authentication">
            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Authentication') }}</h2>
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Google Login') }}</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ __('Allow users to log in or register using Google.') }}</p>
                            @error('auth_google')
                                <p class="text-xs text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="auth_google_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($googleLoginEnabled)>
                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="auth_google_client_id" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Google Client ID') }}</label>
                            <input id="auth_google_client_id" name="auth_google_client_id" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('auth_google_client_id', $googleClientId) }}">
                        </div>
                        <div>
                            <label for="auth_google_client_secret" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Google Client Secret') }}</label>
                            <input id="auth_google_client_secret" name="auth_google_client_secret" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('auth_google_client_secret', $googleClientSecret) }}">
                        </div>
                    </div>
                </div>
            </section>
            <div class="mt-4 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Authentication Settings') }}
                </button>
            </div>
        </form>

        <form x-cloak x-show="tab === 'security'" method="POST" action="{{ route('dashboard.settings.update') }}" class="space-y-8 mt-6">
            @csrf
            <input type="hidden" name="settings_group" value="security">
            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Security') }}</h2>
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Google reCAPTCHA') }}</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ __('Protect the contact and registration forms from spam.') }}</p>
                            @error('security_recaptcha')
                                <p class="text-xs text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="security_recaptcha_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($recaptchaEnabled)>
                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="security_recaptcha_site_key" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Site Key') }}</label>
                            <input id="security_recaptcha_site_key" name="security_recaptcha_site_key" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('security_recaptcha_site_key', $recaptchaSiteKey) }}">
                        </div>
                        <div>
                            <label for="security_recaptcha_secret_key" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Secret Key') }}</label>
                            <input id="security_recaptcha_secret_key" name="security_recaptcha_secret_key" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('security_recaptcha_secret_key', $recaptchaSecretKey) }}">
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Legal Pages') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
                        <div class="space-y-2">
                            <label for="legal_terms_en" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Terms of Service (English)') }}</label>
                            <textarea id="legal_terms_en" name="legal_terms_en" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_terms_en', $legalTermsEn) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="legal_terms_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('شروط الخدمة (العربية)') }}</label>
                            <textarea id="legal_terms_ar" name="legal_terms_ar" dir="rtl" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_terms_ar', $legalTermsAr) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="legal_privacy_en" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Privacy Policy (English)') }}</label>
                            <textarea id="legal_privacy_en" name="legal_privacy_en" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_privacy_en', $legalPrivacyEn) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="legal_privacy_ar" class="block text sm font-medium text-[var(--color-text-muted)]">{{ __('سياسة الخصوصية (العربية)') }}</label>
                            <textarea id="legal_privacy_ar" name="legal_privacy_ar" dir="rtl" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_privacy_ar', $legalPrivacyAr) }}</textarea>
                        </div>
                    </div>
                </div>
            </section>
            <div class="mt-4 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Security Settings') }}
                </button>
            </div>
        </form>

        <form x-cloak x-show="tab === 'notifications'" method="POST" action="{{ route('dashboard.settings.update') }}" class="space-y-8 mt-6">
            @csrf
            <input type="hidden" name="settings_group" value="notifications">
            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Notifications') }}</h2>
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('WhatsApp Floating Button') }}</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ __('Show a subtle WhatsApp chat button on public pages.') }}</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="contact_whatsapp_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($whatsappEnabled)>
                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_whatsapp_phone" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('WhatsApp Phone') }}</label>
                            <input id="contact_whatsapp_phone" name="contact_whatsapp_phone" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('contact_whatsapp_phone', $whatsappPhone) }}" placeholder="+201234567890">
                        </div>
                        <div>
                            <label for="contact_whatsapp_message" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Default Message') }}</label>
                            <input id="contact_whatsapp_message" name="contact_whatsapp_message" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('contact_whatsapp_message', $whatsappMessage) }}">
                        </div>
                    </div>
                </div>
            </section>
            <div class="mt-4 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Notifications Settings') }}
                </button>
            </div>
        </form>

        <form x-cloak x-show="tab === 'landing'" method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data" class="space-y-8 mt-6">
            @csrf
            <input type="hidden" name="settings_group" value="landing">
            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Landing Page') }}</h2>
                <div class="space-y-5">
                    <div>
                        <label for="instructor_name" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Instructor Name') }}</label>
                        <input id="instructor_name" name="instructor_name" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('instructor_name', $instructorName ?? '') }}">
                        @error('instructor_name')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Hero') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle visibility of the hero section.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_hero" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowHero)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Contact Form') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle simple contact form at the bottom.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_contact_form" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowContactForm ?? false)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show About Instructor') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle instructor bio block inside hero.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_about" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowAbout)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Courses Preview') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle featured courses grid.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_courses_preview" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowCoursesPreview)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Testimonials') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle testimonials section.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_testimonials" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowTestimonials)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Footer CTA') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle final call to action.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_footer_cta" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowFooterCta)>
                        </label>
                    </div>
                    <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                        <h3 class="text-base font-semibold text-[var(--color-text-primary)]">{{ __('Hero Title') }}</h3>
                        <p class="text-xs text-[var(--color-text-muted)]">{{ __('Used as the main headline in the landing page hero section.') }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="hero_title_en" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero title (EN)') }}</label>
                                <input id="hero_title_en" name="hero_title_en" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('hero_title_en', $heroTitleEn ?? '') }}">
                            </div>
                            <div>
                                <label for="hero_title_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero title (AR)') }}</label>
                                <input id="hero_title_ar" name="hero_title_ar" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('hero_title_ar', $heroTitleAr ?? '') }}">
                            </div>
                        </div>
                    </section>
                    <section x-data="{ fontTitle: {{ (int) (old('hero_font_title', $heroFontTitle ?? 56)) }}, fontSubtitle: {{ (int) (old('hero_font_subtitle', $heroFontSubtitle ?? 24)) }}, fontDescription: {{ (int) (old('hero_font_description', $heroFontDescription ?? 18)) }} }" class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                        <h3 class="text-base font-semibold text-[var(--color-text-primary)]">{{ __('Hero Typography Settings') }}</h3>
                        <div class="grid grid-cols-1 gap-5">
                            <div class="space-y-2">
                                <label for="hero_font_title" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero Title Font Size') }}</label>
                                <div class="flex items-center gap-3">
                                    <input id="hero_font_title" name="hero_font_title" type="range" min="28" max="96" step="1" class="w-full accent-[var(--color-primary)]" x-model.number="fontTitle">
                                    <span class="text-sm text-[var(--color-text-primary)]" aria-live="polite" x-text="fontTitle + 'px'"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="hero_font_subtitle" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero Subtitle Font Size') }}</label>
                                <div class="flex items-center gap-3">
                                    <input id="hero_font_subtitle" name="hero_font_subtitle" type="range" min="18" max="48" step="1" class="w-full accent-[var(--color-primary)]" x-model.number="fontSubtitle">
                                    <span class="text-sm text-[var(--color-text-primary)]" aria-live="polite" x-text="fontSubtitle + 'px'"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label for="hero_font_description" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero Description Font Size') }}</label>
                                <div class="flex items-center gap-3">
                                    <input id="hero_font_description" name="hero_font_description" type="range" min="14" max="28" step="1" class="w-full accent-[var(--color-primary)]" x-model.number="fontDescription">
                                    <span class="text-sm text-[var(--color-text-primary)]" aria-live="polite" x-text="fontDescription + 'px'"></span>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                        <h3 class="text-base font-semibold text-[var(--color-text-primary)]">{{ __('Hero Subtitle') }}</h3>
                        <p class="text-xs text-[var(--color-text-muted)]">{{ __('Used as the supporting text in the landing page hero section.') }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="hero_subtitle_en" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero subtitle (EN)') }}</label>
                                <input id="hero_subtitle_en" name="hero_subtitle_en" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('hero_subtitle_en', $heroSubtitleEn ?? '') }}">
                            </div>
                            <div>
                                <label for="hero_subtitle_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero subtitle (AR)') }}</label>
                                <input id="hero_subtitle_ar" name="hero_subtitle_ar" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('hero_subtitle_ar', $heroSubtitleAr ?? '') }}">
                            </div>
                        </div>
                    </section>
                    <div>
                        <span class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero Image Display Mode') }}</span>
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_hero_image_mode" value="contain" {{ old('landing_hero_image_mode', $landingHeroImageMode ?? 'contain') === 'contain' ? 'checked' : '' }}>
                                <span class="text-sm text-[var(--color-text-primary)]">{{ __('Fit (Show Full Image)') }}</span>
                            </label>
                            <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_hero_image_mode" value="cover" {{ old('landing_hero_image_mode', $landingHeroImageMode ?? 'contain') === 'cover' ? 'checked' : '' }}>
                                <span class="text-sm text-[var(--color-text-primary)]">{{ __('Fill (Crop to Container)') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label for="landing_feature_1_title" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Feature 1 title') }}</label>
                            <input id="landing_feature_1_title" name="landing_feature_1_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('landing_feature_1_title', $landingFeature1Title) }}">
                            <label for="landing_feature_1_description" class="block text-xs font-medium text-[var(--color-text-muted)]">{{ __('Feature 1 description') }}</label>
                            <textarea id="landing_feature_1_description" name="landing_feature_1_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_1_description', $landingFeature1Description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="landing_feature_2_title" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Feature 2 title') }}</label>
                            <input id="landing_feature_2_title" name="landing_feature_2_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('landing_feature_2_title', $landingFeature2Title) }}">
                            <label for="landing_feature_2_description" class="block text-xs font-medium text-[var(--color-text-muted)]">{{ __('Feature 2 description') }}</label>
                            <textarea id="landing_feature_2_description" name="landing_feature_2_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_2_description', $landingFeature2Description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="landing_feature_3_title" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Feature 3 title') }}</label>
                            <input id="landing_feature_3_title" name="landing_feature_3_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('landing_feature_3_title', $landingFeature3Title) }}">
                            <label for="landing_feature_3_description" class="block text-xs font-medium text-[var(--color-text-muted)]">{{ __('Feature 3 description') }}</label>
                            <textarea id="landing_feature_3_description" name="landing_feature_3_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_3_description', $landingFeature3Description) }}</textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="social_twitter" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Twitter') }}</label>
                            <input id="social_twitter" name="social_twitter" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_twitter', $socialTwitter ?? '') }}" placeholder="https://twitter.com/username">
                        </div>
                        <div>
                            <label for="social_instagram" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Instagram') }}</label>
                            <input id="social_instagram" name="social_instagram" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_instagram', $socialInstagram ?? '') }}" placeholder="https://instagram.com/username">
                        </div>
                        <div>
                            <label for="social_youtube" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('YouTube') }}</label>
                            <input id="social_youtube" name="social_youtube" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_youtube', $socialYouTube ?? '') }}" placeholder="https://youtube.com/@channel">
                        </div>
                        <div>
                            <label for="social_linkedin" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('LinkedIn') }}</label>
                            <input id="social_linkedin" name="social_linkedin" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_linkedin', $socialLinkedIn ?? '') }}" placeholder="https://www.linkedin.com/in/username">
                        </div>
                    </div>
                    <div>
                        <label for="landing_instructor_image" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Instructor hero image') }}</label>
                        <input id="landing_instructor_image" name="landing_instructor_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[var(--color-text-primary)] border-[var(--color-secondary)]/30 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('landing_instructor_image')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($landingInstructorImageUrl)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-[var(--color-text-muted)] mb-1">{{ __('Current hero image') }}</p>
                                <div class="overflow-hidden rounded-xl ring-1 ring-[var(--color-secondary)]/20 shadow-sm">
                                    <img src="{{ $landingInstructorImageUrl }}" alt="Instructor hero" class="w-48 h-48 object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div x-data="{ previewUrl: null }" class="space-y-2">
                        <label for="hero_image" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero Image') }}</label>
                        <input id="hero_image" name="hero_image" type="file" accept="image/jpg,image/png,image/webp" class="mt-1 block w-full text-sm text-[var(--color-text-primary)] border-[var(--color-secondary)]/30 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" @change="const f = $event.target.files[0]; if (f) { const r = new FileReader(); r.onload = e => previewUrl = e.target.result; r.readAsDataURL(f); } else { previewUrl = null }">
                        @error('hero_image')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">{{ __('Recommended size: 1920×1080 (16:9 ratio). Formats: JPG, PNG, WEBP.') }}</p>
                        <div class="mt-3">
                            <p class="text-xs font-medium text-[var(--color-text-muted)] mb-1">{{ __('Preview') }}</p>
                            <div class="inline-flex items-center justify-center rounded-xl border border-[var(--color-secondary)]/20 bg-white shadow-sm p-3">
                                <img x-show="previewUrl" :src="previewUrl" alt="Hero Image" class="h-32 w-64 object-cover rounded-lg">
                                @if ($heroImageUrl)
                                    <img x-show="!previewUrl" src="{{ $heroImageUrl }}" alt="Hero Image" class="h-32 w-64 object-cover rounded-lg">
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="flex items-center gap-2 text-sm text-[var(--color-text-muted)]">
                                <input type="checkbox" name="remove_hero_image" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                {{ __('Remove Hero Image (reset to default)') }}
                            </label>
                        </div>
                    </div>
                </div>
            </section>
            <div class="mt-4 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Landing Settings') }}
                </button>
            </div>
        </form>

        <form x-cloak x-show="false" method="POST" action="{{ route('dashboard.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('General Settings') }}
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="default_language" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Default Language') }}
                        </label>
                        <select id="default_language" name="default_language" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                            <option value="en" @selected($defaultLanguage === 'en')>English</option>
                            <option value="ar" @selected($defaultLanguage === 'ar')>العربية</option>
                        </select>
                        @error('default_language')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Site Logo') }}
                        </label>
                        <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[var(--color-text-primary)] border-[var(--color-secondary)]/30 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('logo')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($logoUrl)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-[var(--color-text-muted)] mb-1">{{ __('Current logo') }}</p>
                                <div class="inline-flex items-center justify-center rounded-xl border border-[var(--color-secondary)]/20 bg-white shadow-sm p-3">
                                    <img src="{{ $logoUrl }}" alt="Logo" class="h-16 w-16 object-contain">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Appearance') }}
                </h2>
                <p class="text-xs text-[var(--color-text-muted)]">
                    {{ __('Update brand colors used across buttons, CTAs and the landing page.') }}
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-1">{{ __('Primary Color') }}</label>
                        <input type="color" name="primary" value="{{ $theme['primary'] ?? '#3A5BA9' }}" class="h-10 w-16 border rounded">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">{{ __('Main actions and highlights.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-1">{{ __('Secondary Color') }}</label>
                        <input type="color" name="secondary" value="{{ $theme['secondary'] ?? '#2F3C4F' }}" class="h-10 w-16 border rounded">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">{{ __('Links and secondary actions.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-muted)] mb-1">{{ __('Accent Color') }}</label>
                        <input type="color" name="accent" value="{{ $theme['accent'] ?? '#0FA3A4' }}" class="h-10 w-16 border rounded">
                        <p class="text-xs text-[var(--color-text-muted)] mt-1">{{ __('Status badges and success.') }}</p>
                    </div>
                    <div class="md:col-span-3">
                        <a href="{{ route('dashboard.appearance.edit') }}" class="ml-3 inline-flex items-center text-sm font-medium text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">
                            {{ __('Open full Appearance settings') }}
                        </a>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Legal Pages') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="legal_terms_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Terms of Service (English)') }}
                        </label>
                        <textarea id="legal_terms_en" name="legal_terms_en" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_terms_en', $legalTermsEn) }}</textarea>
                        @error('legal_terms_en')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="legal_terms_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('شروط الخدمة (العربية)') }}
                        </label>
                        <textarea id="legal_terms_ar" name="legal_terms_ar" dir="rtl" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_terms_ar', $legalTermsAr) }}</textarea>
                        @error('legal_terms_ar')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="legal_privacy_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Privacy Policy (English)') }}
                        </label>
                        <textarea id="legal_privacy_en" name="legal_privacy_en" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_privacy_en', $legalPrivacyEn) }}</textarea>
                        @error('legal_privacy_en')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="legal_privacy_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('سياسة الخصوصية (العربية)') }}
                        </label>
                        <textarea id="legal_privacy_ar" name="legal_privacy_ar" dir="rtl" rows="10" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('legal_privacy_ar', $legalPrivacyAr) }}</textarea>
                        @error('legal_privacy_ar')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Payment Methods') }}
                </h2>
                <div class="space-y-5">
                    <div class="rounded-md border border-[var(--color-secondary)]/20 bg-white p-3">
                        <p class="text-xs text-[var(--color-text-muted)]">
                            {{ app()->getLocale() === 'ar' ? 'شراء لمرة واحدة · وصول مدى الحياة · بدون رسوم شهرية' : 'One‑time purchase. Lifetime access. No monthly fees.' }}
                        </p>
                    </div>
                    <div class="rounded-md border border-[var(--color-secondary)]/20 p-4 space-y-4">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Online Payments') }}</p>
                        <div class="rounded-md border border-[var(--color-secondary)]/20 bg-white p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Stripe Configuration') }}</p>
                                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Manage Stripe keys and mode for demo or production.') }}</p>
                                </div>
                                @php $stripeConnectedTest = $paymentsStripeEnabled && $stripeHasSecret && str_starts_with((string) $stripePublishableKey, 'pk_') && $stripeMode === 'test'; @endphp
                                @if ($stripeConnectedTest)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-[var(--color-primary)]/20 bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-xs">{{ __('Stripe connected (Test mode)') }}</span>
                                @endif
                                @if (! empty($paymentsStripeEnabled))
                                    @if (! empty($stripeWebhookSecretExists))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-[var(--color-primary)]/20 bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-xs">{{ __('Webhook ready') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-[var(--color-error)]/20 bg-[var(--color-error)]/10 text-[var(--color-error)] text-xs">{{ __('Webhook not configured') }}</span>
                                    @endif
                                @endif
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                                    <div>
                                        <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Enable Stripe payments') }}</p>
                                        <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle Stripe checkout throughout the site.') }}</p>
                                    </div>
                                    <input type="checkbox" name="payments_stripe_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($paymentsStripeEnabled)>
                                </label>
                                <div>
                                    <label for="stripe_mode" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Mode') }}</label>
                                    <select id="stripe_mode" name="stripe_mode" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                        <option value="test" @selected(old('stripe_mode', $stripeMode) === 'test')>{{ __('Test') }}</option>
                                        <option value="live" @selected(old('stripe_mode', $stripeMode) === 'live')>{{ __('Live') }}</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="stripe_publishable_key" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Stripe Publishable Key') }}</label>
                                    <input id="stripe_publishable_key" name="stripe_publishable_key" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('stripe_publishable_key', $stripePublishableKey) }}" placeholder="pk_test_...">
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="stripe_secret_key" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Stripe Secret Key') }}</label>
                                    <input id="stripe_secret_key" name="stripe_secret_key" type="password" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder="sk_test_...">
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="stripe_webhook_endpoint" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Stripe Webhook Endpoint') }}</label>
                                    <div x-data="{ copied: false }" class="mt-1 relative">
                                        <input id="stripe_webhook_endpoint" type="text" readonly class="block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm pr-10 focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ url('/webhooks/stripe') }}">
                                        <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]" aria-label="{{ __('Copy') }}"
                                            @click="navigator.clipboard.writeText(document.getElementById('stripe_webhook_endpoint').value); copied = true">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 9V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-4M7 7H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ __('Add this URL to Stripe → Developers → Webhooks') }}</p>
                                    <div class="mt-2 text-xs text-[var(--color-text-muted)]">
                                        <p class="font-medium">{{ __('Required events:') }}</p>
                                        <ul class="list-disc ml-4 space-y-0.5">
                                            <li><code>checkout.session.completed</code></li>
                                            <li><code>payment_intent.succeeded</code></li>
                                        </ul>
                                    </div>
                                    <div class="mt-3 rounded-md border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 p-3 space-y-2">
                                        <p class="text-xs text-[var(--color-text-muted)]">
                                            {{ __('Note: Stripe Dashboard cannot reach local domains directly. Use Stripe CLI to forward webhooks to your local Sail domain.') }}
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-[var(--color-text-primary)] font-medium">{{ __('Stripe CLI command') }}:</span>
                                            <code class="text-xs px-2 py-1 rounded bg-white border border-[var(--color-secondary)]/30 text-[var(--color-text-primary)]">stripe listen --forward-to {{ url('/webhooks/stripe') }}</code>
                                        </div>
                                    </div>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="stripe_webhook_secret" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Stripe Webhook Secret') }}</label>
                                    <input id="stripe_webhook_secret" name="stripe_webhook_secret" type="password" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder="whsec_..." value="{{ ! empty($stripeWebhookSecretExists) ? '••••••••' : '' }}">
                                    <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ __('Store your Stripe signing secret. For local dev, copy it from Stripe CLI output after running stripe listen. The value is masked after save.') }}</p>
                                </div>
                            </div>
                            <div class="rounded-md border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 p-3">
                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Where do I get these keys?') }}</p>
                                <ul class="mt-2 text-xs text-[var(--color-text-muted)] space-y-1">
                                    <li>{{ __('Go to') }} <a href="https://dashboard.stripe.com" target="_blank" rel="noopener" class="underline text-[var(--color-primary)]">https://dashboard.stripe.com</a></li>
                                    <li>{{ __('Switch to Test Mode (top right)') }}</li>
                                    <li>{{ __('Open Developers → API keys') }}</li>
                                    <li>{{ __('Copy: Publishable key (starts with pk_) and Secret key (starts with sk_)') }}</li>
                                    <li>{{ __('Test keys are safe for demos and local testing') }}</li>
                                </ul>
                                @error('stripe')
                                    <p class="text-xs text-[var(--color-error)] mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="rounded-md border border-[var(--color-secondary)]/20 bg-white p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('PayPal Configuration') }}</p>
                                    <p class="text-xs text-[var(--color-text-muted)]">{{ __('Manage PayPal credentials and mode for demo or production.') }}</p>
                                </div>
                                @php $paypalConnected = $paymentsPaypalEnabled && $paypalHasSecret && $paypalClientId !== ''; @endphp
                                @if ($paypalConnected)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border border-[var(--color-primary)]/20 bg-[var(--color-primary)]/10 text-[var(--color-primary)] text-xs">
                                        {{ $paypalMode === 'sandbox' ? __('PayPal connected (Sandbox)') : __('PayPal connected (Live)') }}
                                    </span>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                                    <div>
                                        <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Enable PayPal payments') }}</p>
                                        <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle PayPal checkout throughout the site.') }}</p>
                                    </div>
                                    <input type="checkbox" name="payments_paypal_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($paymentsPaypalEnabled)>
                                </label>
                                <div>
                                    <label for="paypal_mode" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Mode') }}</label>
                                    <select id="paypal_mode" name="paypal_mode" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                        <option value="sandbox" @selected(old('paypal_mode', $paypalMode) === 'sandbox')>{{ __('Sandbox') }}</option>
                                        <option value="live" @selected(old('paypal_mode', $paypalMode) === 'live')>{{ __('Live') }}</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="paypal_client_id" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('PayPal Client ID') }}</label>
                                    <input id="paypal_client_id" name="paypal_client_id" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('paypal_client_id', $paypalClientId) }}" placeholder="A...">
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="paypal_client_secret" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('PayPal Client Secret') }}</label>
                                    <input id="paypal_client_secret" name="paypal_client_secret" type="password" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder="E...">
                                </div>
                            </div>
                            <div class="rounded-md border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 p-3">
                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Where do I get these PayPal credentials?') }}</p>
                                <ul class="mt-2 text-xs text-[var(--color-text-muted)] space-y-1">
                                    <li>{{ __('Go to') }} <a href="https://developer.paypal.com" target="_blank" rel="noopener" class="underline text-[var(--color-primary)]">https://developer.paypal.com</a></li>
                                    <li>{{ __('Log in with your PayPal account') }}</li>
                                    <li>{{ __('Go to Dashboard → My Apps & Credentials') }}</li>
                                    <li>{{ __('Under Sandbox or Live: Copy Client ID and Secret') }}</li>
                                    <li>{{ __('Sandbox = testing/demo, Live = real payments') }}</li>
                                    <li>{{ __('Sandbox credentials are safe for demos and local testing') }}</li>
                                </ul>
                                @error('paypal')
                                    <p class="text-xs text-[var(--color-error)] mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="payments_manual_instructions" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Manual payment instructions') }}
                        </label>
                        <textarea id="payments_manual_instructions" name="payments_manual_instructions" rows="4" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('payments_manual_instructions', $paymentsManualInstructions) }}</textarea>
                        @error('payments_manual_instructions')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-[var(--color-text-muted)]">
                            {{ __('These instructions will be shown to students choosing manual payments (bank transfer, cash, etc.).') }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Authentication') }}
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Google Login') }}</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ __('Allow users to log in or register using Google.') }}</p>
                            @error('auth_google')
                                <p class="text-xs text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="auth_google_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($googleLoginEnabled)>
                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="auth_google_client_id" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Google Client ID') }}</label>
                            <input id="auth_google_client_id" name="auth_google_client_id" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('auth_google_client_id', $googleClientId) }}">
                        </div>
                        <div>
                            <label for="auth_google_client_secret" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Google Client Secret') }}</label>
                            <input id="auth_google_client_secret" name="auth_google_client_secret" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('auth_google_client_secret', $googleClientSecret) }}">
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Security') }}
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Google reCAPTCHA') }}</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ __('Protect the contact and registration forms from spam.') }}</p>
                            @error('security_recaptcha')
                                <p class="text-xs text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="security_recaptcha_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($recaptchaEnabled)>
                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="security_recaptcha_site_key" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Site Key') }}</label>
                            <input id="security_recaptcha_site_key" name="security_recaptcha_site_key" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('security_recaptcha_site_key', $recaptchaSiteKey) }}">
                        </div>
                        <div>
                            <label for="security_recaptcha_secret_key" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Secret Key') }}</label>
                            <input id="security_recaptcha_secret_key" name="security_recaptcha_secret_key" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('security_recaptcha_secret_key', $recaptchaSecretKey) }}">
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Contact') }}
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('WhatsApp Floating Button') }}</p>
                            <p class="text-xs text-[var(--color-text-muted)]">{{ __('Show a subtle WhatsApp chat button on public pages.') }}</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="contact_whatsapp_enabled" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)] mr-2" @checked($whatsappEnabled)>
                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('Enabled') }}</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_whatsapp_phone" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('WhatsApp Phone') }}</label>
                            <input id="contact_whatsapp_phone" name="contact_whatsapp_phone" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('contact_whatsapp_phone', $whatsappPhone) }}" placeholder="+201234567890">
                        </div>
                        <div>
                            <label for="contact_whatsapp_message" class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Default Message') }}</label>
                            <input id="contact_whatsapp_message" name="contact_whatsapp_message" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('contact_whatsapp_message', $whatsappMessage) }}">
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-sm border border-[var(--color-secondary)]/10 p-6 space-y-5">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">
                    {{ __('Landing Page') }}
                </h2>
                <div class="space-y-5">
                    <div>
                        <label for="instructor_name" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Instructor Name') }}
                        </label>
                        <input id="instructor_name" name="instructor_name" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('instructor_name', $instructorName ?? '') }}">
                        @error('instructor_name')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Hero') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle visibility of the hero section.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_hero" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowHero)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Contact Form') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle simple contact form at the bottom.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_contact_form" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowContactForm ?? false)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show About Instructor') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle instructor bio block inside hero.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_about" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowAbout)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Courses Preview') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle featured courses grid.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_courses_preview" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowCoursesPreview)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Testimonials') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle testimonials section.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_testimonials" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowTestimonials)>
                        </label>
                        <label class="flex items-center justify-between rounded-md border border-[var(--color-secondary)]/20 p-3">
                            <div>
                                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Show Footer CTA') }}</p>
                                <p class="text-xs text-[var(--color-text-muted)]">{{ __('Toggle final call to action.') }}</p>
                            </div>
                            <input type="checkbox" name="landing_show_footer_cta" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($landingShowFooterCta)>
                        </label>
                    </div>
                    <div>
                        <label for="landing_hero_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Hero title') }}
                        </label>
                        <input id="landing_hero_title" name="landing_hero_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('landing_hero_title', $landingHeroTitle) }}">
                        @error('landing_hero_title')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="landing_hero_title_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero title (EN)') }}
                            </label>
                            <input id="landing_hero_title_en" name="landing_hero_title_en" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_hero_title_en', $landingHeroTitleEn ?? '') }}">
                        </div>
                        <div>
                            <label for="landing_hero_title_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero title (AR)') }}
                            </label>
                            <input id="landing_hero_title_ar" name="landing_hero_title_ar" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_hero_title_ar', $landingHeroTitleAr ?? '') }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="hero_title_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero title (EN, override)') }}
                            </label>
                            <input id="hero_title_en" name="hero_title_en" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('hero_title_en', $heroTitleEn ?? '') }}">
                        </div>
                        <div>
                            <label for="hero_title_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero title (AR, override)') }}
                            </label>
                            <input id="hero_title_ar" name="hero_title_ar" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('hero_title_ar', $heroTitleAr ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <label for="landing_hero_subtitle" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Hero subtitle') }}
                        </label>
                        <input id="landing_hero_subtitle" name="landing_hero_subtitle" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                               value="{{ old('landing_hero_subtitle', $landingHeroSubtitle) }}">
                        @error('landing_hero_subtitle')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="landing_hero_subtitle_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero subtitle (EN)') }}
                            </label>
                            <input id="landing_hero_subtitle_en" name="landing_hero_subtitle_en" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_hero_subtitle_en', $landingHeroSubtitleEn ?? '') }}">
                        </div>
                        <div>
                            <label for="landing_hero_subtitle_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero subtitle (AR)') }}
                            </label>
                            <input id="landing_hero_subtitle_ar" name="landing_hero_subtitle_ar" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_hero_subtitle_ar', $landingHeroSubtitleAr ?? '') }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="hero_subtitle_en" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero subtitle (EN, override)') }}
                            </label>
                            <input id="hero_subtitle_en" name="hero_subtitle_en" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('hero_subtitle_en', $heroSubtitleEn ?? '') }}">
                        </div>
                        <div>
                            <label for="hero_subtitle_ar" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Hero subtitle (AR, override)') }}
                            </label>
                            <input id="hero_subtitle_ar" name="hero_subtitle_ar" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('hero_subtitle_ar', $heroSubtitleAr ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Hero Image Display Mode') }}</span>
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_hero_image_mode" value="contain" {{ old('landing_hero_image_mode', $landingHeroImageMode ?? 'contain') === 'contain' ? 'checked' : '' }}>
                                <span class="text-sm text-[var(--color-text-primary)]">{{ __('Fit (Show Full Image)') }}</span>
                            </label>
                            <label class="flex items-center gap-2 rounded-md border border-[var(--color-secondary)]/20 p-3 cursor-pointer">
                                <input type="radio" name="landing_hero_image_mode" value="cover" {{ old('landing_hero_image_mode', $landingHeroImageMode ?? 'contain') === 'cover' ? 'checked' : '' }}>
                                <span class="text-sm text-[var(--color-text-primary)]">{{ __('Fill (Crop to Container)') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label for="landing_feature_1_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 1 title') }}
                            </label>
                            <input id="landing_feature_1_title" name="landing_feature_1_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_1_title', $landingFeature1Title) }}">
                            <label for="landing_feature_1_description" class="block text-xs font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 1 description') }}
                            </label>
                            <textarea id="landing_feature_1_description" name="landing_feature_1_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_1_description', $landingFeature1Description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="landing_feature_2_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 2 title') }}
                            </label>
                            <input id="landing_feature_2_title" name="landing_feature_2_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_2_title', $landingFeature2Title) }}">
                            <label for="landing_feature_2_description" class="block text-xs font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 2 description') }}
                            </label>
                            <textarea id="landing_feature_2_description" name="landing_feature_2_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_2_description', $landingFeature2Description) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label for="landing_feature_3_title" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 3 title') }}
                            </label>
                            <input id="landing_feature_3_title" name="landing_feature_3_title" type="text" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]"
                                   value="{{ old('landing_feature_3_title', $landingFeature3Title) }}">
                            <label for="landing_feature_3_description" class="block text-xs font-medium text-[var(--color-text-muted)]">
                                {{ __('Feature 3 description') }}
                            </label>
                            <textarea id="landing_feature_3_description" name="landing_feature_3_description" rows="3" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('landing_feature_3_description', $landingFeature3Description) }}</textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="social_twitter" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Twitter') }}
                            </label>
                            <input id="social_twitter" name="social_twitter" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_twitter', $socialTwitter ?? '') }}" placeholder="https://twitter.com/username">
                        </div>
                        <div>
                            <label for="social_instagram" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('Instagram') }}
                            </label>
                            <input id="social_instagram" name="social_instagram" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_instagram', $socialInstagram ?? '') }}" placeholder="https://instagram.com/username">
                        </div>
                        <div>
                            <label for="social_youtube" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('YouTube') }}
                            </label>
                            <input id="social_youtube" name="social_youtube" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_youtube', $socialYouTube ?? '') }}" placeholder="https://youtube.com/@channel">
                        </div>
                        <div>
                            <label for="social_linkedin" class="block text-sm font-medium text-[var(--color-text-muted)]">
                                {{ __('LinkedIn') }}
                            </label>
                            <input id="social_linkedin" name="social_linkedin" type="url" class="mt-1 block w-full rounded-md border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" value="{{ old('social_linkedin', $socialLinkedIn ?? '') }}" placeholder="https://www.linkedin.com/in/username">
                        </div>
                    </div>
                    <div>
                        <label for="landing_instructor_image" class="block text-sm font-medium text-[var(--color-text-muted)]">
                            {{ __('Instructor hero image') }}
                        </label>
                        <input id="landing_instructor_image" name="landing_instructor_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[var(--color-text-primary)] border-[var(--color-secondary)]/30 rounded-md shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        @error('landing_instructor_image')
                            <p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($landingInstructorImageUrl)
                            <div class="mt-3">
                                <p class="text-xs font-medium text-[var(--color-text-muted)] mb-1">{{ __('Current hero image') }}</p>
                                <div class="overflow-hidden rounded-xl ring-1 ring-[var(--color-secondary)]/20 shadow-sm">
                                    <img src="{{ $landingInstructorImageUrl }}" alt="Instructor hero" class="w-48 h-48 object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <div class="mt-8 flex items-center justify-between gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-[var(--color-secondary)]/30 bg-white text-sm font-medium text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Save Settings') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
