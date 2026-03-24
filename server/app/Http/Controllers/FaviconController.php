<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Response;

class FaviconController extends Controller
{
    public function __invoke(SettingsService $settings): Response
    {
        $docsLogo = base_path('../docs/assets/logo.png');
        if (file_exists($docsLogo)) {
            return response()->file($docsLogo, ['Content-Type' => 'image/png']);
        }

        $logoPath = $settings->get('site.logo_path');
        if ($logoPath) {
            $stored = storage_path('app/public/'.$logoPath);
            if (file_exists($stored)) {
                return response()->file($stored, ['Content-Type' => 'image/png']);
            }
        }

        $ico = public_path('favicon.ico');
        if (file_exists($ico)) {
            return response()->file($ico, ['Content-Type' => 'image/x-icon']);
        }

        abort(404);
    }
}
