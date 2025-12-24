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

        $titleSize = (int) ($this->settings->get('hero.font.title', 56));
        $subtitleSize = (int) ($this->settings->get('hero.font.subtitle', 24));
        $descriptionSize = (int) ($this->settings->get('hero.font.description', 18));
        $heroTypography = [
            'title' => max(28, min(96, $titleSize)).'px',
            'subtitle' => max(18, min(48, $subtitleSize)).'px',
            'description' => max(14, min(28, $descriptionSize)).'px',
        ];
        View::share('heroTypography', $heroTypography);

        return $next($request);
    }
}
