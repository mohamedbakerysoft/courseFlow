<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Appearance\UpdateAppearanceRequest;
use App\Models\Setting;
use App\Services\SettingsService;

class AppearanceController extends Controller
{
    public function edit(SettingsService $settings)
    {
        $primary = (string) $settings->get('theme.primary', '#F5B800');
        $secondary = (string) $settings->get('theme.secondary', '#0B0B0B');
        $accent = (string) $settings->get('theme.accent', '#F7F7F7');

        return view('dashboard.appearance.edit', compact(
            'primary',
            'secondary',
            'accent',
        ));
    }

    public function update(UpdateAppearanceRequest $request, SettingsService $settings)
    {
        $validated = $request->validated();
        $settings->set([
            'theme.primary' => $validated['primary'],
            'theme.secondary' => $validated['secondary'],
            'theme.accent' => $validated['accent'],
            'typography.english_font' => 'Poppins',
        ]);

        Setting::ensureValue('landing.layout', 'default');
        Setting::deleteByKeys(['typography.arabic_font']);

        return back()->with('status', 'Appearance updated.');
    }
}
