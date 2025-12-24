<?php

namespace App\Http\Middleware;

use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;

class EnsureNotInstalled
{
    public function handle(Request $request, Closure $next)
    {
        $installed = (bool) app(SettingsService::class)->get('app.installed', false);
        if ($installed) {
            return redirect()->to('/');
        }

        return $next($request);
    }
}
