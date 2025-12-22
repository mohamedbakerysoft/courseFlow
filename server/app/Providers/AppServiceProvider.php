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
        $defaults = [
            'primary' => '#3A5BA9',
            'secondary' => '#2F3C4F',
            'accent' => '#0FA3A4',
            'bg' => '#F3F5F9',
            'text' => '#0B1221',
            'text_muted' => '#5C6E86',
            'primary_hover' => '#2F4F97',
        ];
        $theme = $defaults;
        try {
            foreach (['theme.primary' => 'primary', 'theme.secondary' => 'secondary', 'theme.accent' => 'accent'] as $key => $map) {
                $row = Setting::query()->where('key', $key)->first();
                if ($row && is_string($row->value) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $row->value)) {
                    $theme[$map] = $row->value;
                }
            }
        } catch (\Throwable $e) {
            $theme = $defaults;
        }
        View::share('theme', $theme);

        // Typography (fonts)
        $typographyDefaults = [
            'arabic_font' => 'Cairo',
            'english_font' => 'Inter',
        ];
        $typography = $typographyDefaults;
        try {
            foreach (['typography.arabic_font' => 'arabic_font', 'typography.english_font' => 'english_font'] as $key => $map) {
                $row = Setting::query()->where('key', $key)->first();
                if ($row && is_string($row->value) && $row->value !== '') {
                    $typography[$map] = $row->value;
                }
            }
        } catch (\Throwable $e) {
            $typography = $typographyDefaults;
        }
        $fontStacks = [
            // Arabic stacks
            'Cairo' => "'Cairo', sans-serif",
            'Tajawal' => "'Tajawal', sans-serif",
            'IBM Plex Arabic' => "'IBM Plex Arabic', sans-serif",
            // English stacks
            'Inter' => "'Inter', system-ui, sans-serif",
            'Poppins' => "'Poppins', system-ui, sans-serif",
            'Roboto' => "'Roboto', system-ui, sans-serif",
        ];
        $typographyCss = [
            'arabic_stack' => $fontStacks[$typography['arabic_font']] ?? $fontStacks['Cairo'],
            'english_stack' => $fontStacks[$typography['english_font']] ?? $fontStacks['Inter'],
        ];
        View::share('typography', $typography);
        View::share('typographyCss', $typographyCss);

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
}
