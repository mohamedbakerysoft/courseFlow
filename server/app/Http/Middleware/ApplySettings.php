<?php

namespace App\Http\Middleware;

use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $locale = 'en';

        app()->setLocale($locale);

        $logoPath = (string) $this->settings->get('site.logo_path', '');
        $logoUrl = $logoPath !== '' && Storage::disk('public')->exists($logoPath)
            ? asset('storage/'.$logoPath)
            : null;
        $siteBrandName = trim((string) $this->settings->get('site.brand_name', '')) ?: config('app.name', 'Learnova');
        $siteBrandSlogan = trim((string) $this->settings->get('site.brand_slogan', '')) ?: 'Premium learning storefront';

        View::share([
            'appLocale' => $locale,
            'siteLogoUrl' => $logoUrl,
            'siteBrandName' => $siteBrandName,
            'siteBrandSlogan' => $siteBrandSlogan,
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
        View::share('rightClickEnabled', (bool) $this->settings->get('security.right_click.enabled', true));

        return $next($request);
    }
}
