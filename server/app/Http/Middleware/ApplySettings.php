<?php

namespace App\Http\Middleware;

use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ApplySettings
{
    protected SettingsService $settings;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $locale = (string) $this->settings->get('site.default_language', config('app.locale'));

        app()->setLocale($locale);

        $isRtl = in_array($locale, ['ar'], true);

        $logoPath = $this->settings->get('site.logo_path');
        $logoUrl = $logoPath ? asset('storage/'.$logoPath) : null;

        View::share([
            'appLocale' => $locale,
            'isRtl' => $isRtl,
            'siteLogoUrl' => $logoUrl,
        ]);

        return $next($request);
    }
}

