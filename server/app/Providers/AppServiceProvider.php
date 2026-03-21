<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const THEME_DEFAULTS = [
        'primary' => '#F5B800',
        'secondary' => '#0B0B0B',
        'accent' => '#F7F7F7',
        'bg' => '#FFFFFF',
        'text' => '#0B0B0B',
        'text_muted' => '#4A4A4A',
        'primary_hover' => '#D8A100',
        'error' => '#DC2626',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment(['local', 'demo']) && config('database.default') === 'sqlite') {
            $databasePath = config('database.connections.sqlite.database');
            if ($databasePath && $databasePath !== ':memory:' && ! file_exists($databasePath)) {
                @touch($databasePath);
            }
        }

        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(User::class, \App\Policies\UserPolicy::class);

        // Theme (colors)
        $defaults = self::THEME_DEFAULTS;
        $theme = $defaults;
        $defaultUiTheme = 'system';
        try {
            foreach ([
                'theme.primary' => 'primary',
                'theme.secondary' => 'secondary',
                'theme.accent' => 'accent',
                'theme.bg' => 'bg',
                'theme.text' => 'text',
                'theme.text_muted' => 'text_muted',
                'theme.primary_hover' => 'primary_hover',
                'theme.error' => 'error',
            ] as $key => $map) {
                $row = Setting::query()->where('key', $key)->first();
                if ($row && is_string($row->value) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $row->value)) {
                    $theme[$map] = $row->value;
                }
            }

            if ($theme['primary_hover'] === $defaults['primary_hover'] && $theme['primary'] !== $defaults['primary']) {
                $theme['primary_hover'] = $this->shiftHexBrightness($theme['primary'], -0.12);
            }

            if ($theme['text'] === $defaults['text'] && $theme['secondary'] !== $defaults['secondary']) {
                $theme['text'] = $theme['secondary'];
            }

            $savedDefaultTheme = (string) (Setting::query()->where('key', 'ui.theme.default')->value('value') ?? 'system');
            if (in_array($savedDefaultTheme, ['light', 'dark', 'system'], true)) {
                $defaultUiTheme = $savedDefaultTheme;
            }
        } catch (\Throwable $e) {
            $theme = $defaults;
            $defaultUiTheme = 'system';
        }
        View::share('theme', $theme);
        View::share('defaultUiTheme', $defaultUiTheme);

        // Hero typography (font sizes)
        try {
            $titleRow = Setting::query()->where('key', 'hero.font.title')->first();
            $subtitleRow = Setting::query()->where('key', 'hero.font.subtitle')->first();
            $descriptionRow = Setting::query()->where('key', 'hero.font.description')->first();
            $titleVal = is_numeric($titleRow?->value) ? (int) $titleRow->value : 56;
            $subtitleVal = is_numeric($subtitleRow?->value) ? (int) $subtitleRow->value : 24;
            $descriptionVal = is_numeric($descriptionRow?->value) ? (int) $descriptionRow->value : 18;
            $heroTypography = [
                'title' => max(28, min(96, $titleVal)).'px',
                'subtitle' => max(18, min(48, $subtitleVal)).'px',
                'description' => max(14, min(28, $descriptionVal)).'px',
            ];
        } catch (\Throwable $e) {
            $heroTypography = [
                'title' => '56px',
                'subtitle' => '24px',
                'description' => '18px',
            ];
        }
        View::share('heroTypography', $heroTypography);

        // Security: reCAPTCHA settings -> config override
        try {
            $recaptchaEnabledRow = Setting::query()->where('key', 'security.recaptcha.enabled')->first();
            $recaptchaSiteKeyRow = Setting::query()->where('key', 'security.recaptcha.site_key')->first();
            $recaptchaSecretKeyRow = Setting::query()->where('key', 'security.recaptcha.secret_key')->first();
            $recaptchaEnabled = (bool) ($recaptchaEnabledRow?->value ?? config('services.recaptcha.enabled'));
            $recaptchaSiteKey = (string) ($recaptchaSiteKeyRow?->value ?? config('services.recaptcha.site_key'));
            $recaptchaSecretKey = (string) ($recaptchaSecretKeyRow?->value ?? config('services.recaptcha.secret_key'));
            config([
                'services.recaptcha.enabled' => $recaptchaEnabled,
                'services.recaptcha.site_key' => $recaptchaSiteKey,
                'services.recaptcha.secret_key' => $recaptchaSecretKey,
            ]);
        } catch (\Throwable $e) {
            // noop
        }

        // Authentication: Google login settings -> config override
        try {
            $googleEnabledRow = Setting::query()->where('key', 'auth.google.enabled')->first();
            $googleClientIdRow = Setting::query()->where('key', 'auth.google.client_id')->first();
            $googleClientSecretRow = Setting::query()->where('key', 'auth.google.client_secret')->first();
            $googleEnabled = (bool) ($googleEnabledRow?->value ?? config('services.google.enabled'));
            $googleClientId = (string) ($googleClientIdRow?->value ?? config('services.google.client_id'));
            $googleClientSecret = (string) ($googleClientSecretRow?->value ?? config('services.google.client_secret'));
            config([
                'services.google.enabled' => $googleEnabled,
                'services.google.client_id' => $googleClientId,
                'services.google.client_secret' => $googleClientSecret,
            ]);
        } catch (\Throwable $e) {
            // noop
        }

        // Payments: PayPal settings -> config override
        try {
            $paypalModeRow = Setting::query()->where('key', 'paypal.mode')->first();
            $paypalClientIdRow = Setting::query()->where('key', 'paypal.client_id')->first();
            $paypalClientSecretRow = Setting::query()->where('key', 'paypal.client_secret')->first();
            $paypalWebhookSecretRow = Setting::query()->where('key', 'paypal.webhook_secret')->first();
            $mode = (string) ($paypalModeRow?->value ?? 'sandbox');
            $clientId = (string) ($paypalClientIdRow?->value ?? config('services.paypal.client_id'));
            $clientSecret = (string) ($paypalClientSecretRow?->value ?? config('services.paypal.client_secret'));
            $webhookSecret = (string) ($paypalWebhookSecretRow?->value ?? config('services.paypal.webhook_secret'));
            $baseUrl = $mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
            config([
                'services.paypal.client_id' => $clientId,
                'services.paypal.client_secret' => $clientSecret,
                'services.paypal.webhook_secret' => $webhookSecret,
                'services.paypal.base_url' => $baseUrl,
            ]);
        } catch (\Throwable $e) {
            // noop
        }

        try {
            $demoEnabled = filter_var(
                Setting::query()->where('key', 'demo.enabled')->value('value') ?? config('demo.enabled'),
                FILTER_VALIDATE_BOOL,
                FILTER_NULL_ON_FAILURE
            );

            config([
                'demo.enabled' => $demoEnabled ?? (bool) config('demo.enabled'),
            ]);
        } catch (\Throwable $e) {
            // noop
        }

        // Contact: WhatsApp CTA shared to public layout
        try {
            $waEnabled = (bool) (Setting::query()->where('key', 'contact.whatsapp.enabled')->value('value') ?? false);
            $waPhone = (string) (Setting::query()->where('key', 'contact.whatsapp.phone')->value('value') ?? '');
            $waMessage = (string) (Setting::query()->where('key', 'contact.whatsapp.message')->value('value') ?? '');
            View::share('whatsappCta', [
                'enabled' => $waEnabled && $waPhone !== '',
                'phone' => $waPhone,
                'message' => $waMessage,
            ]);
        } catch (\Throwable $e) {
        View::share('whatsappCta', [
                'enabled' => false,
                'phone' => '',
                'message' => '',
            ]);
        }
    }

    private function shiftHexBrightness(string $hex, float $percentage): string
    {
        $normalized = ltrim($hex, '#');

        if (strlen($normalized) === 3) {
            $normalized = preg_replace('/(.)/', '$1$1', $normalized);
        }

        if (! is_string($normalized) || strlen($normalized) !== 6) {
            return $hex;
        }

        $channels = str_split($normalized, 2);
        $adjusted = array_map(function (string $channel) use ($percentage): string {
            $value = hexdec($channel);
            $nextValue = $percentage < 0
                ? (int) round($value * (1 + $percentage))
                : (int) round($value + ((255 - $value) * $percentage));

            return str_pad(dechex(max(0, min(255, $nextValue))), 2, '0', STR_PAD_LEFT);
        }, $channels);

        return '#'.strtoupper(implode('', $adjusted));
    }
}
