<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    public function edit(SettingsService $settings)
    {
        $primary = optional(Setting::where('key', 'theme.primary')->first())->value ?: '#F5B800';
        $secondary = optional(Setting::where('key', 'theme.secondary')->first())->value ?: '#0B0B0B';
        $accent = optional(Setting::where('key', 'theme.accent')->first())->value ?: '#F7F7F7';
        $englishFont = optional(Setting::where('key', 'typography.english_font')->first())->value ?: 'Manrope';

        $landingLayout = (string) $settings->get('landing.layout', 'default');

        return view('dashboard.appearance.edit', compact(
            'primary',
            'secondary',
            'accent',
            'englishFont',
            'landingLayout',
        ));
    }

    public function update(Request $request, SettingsService $settings)
    {
        $validated = $request->validate([
            'primary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'secondary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'accent' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'english_font' => ['required', 'in:Manrope'],
            'landing_layout' => ['nullable', 'in:default,layout_v2,layout_v3'],
        ]);
        $settings->set([
            'theme.primary' => $validated['primary'],
            'theme.secondary' => $validated['secondary'],
            'theme.accent' => $validated['accent'],
            'typography.english_font' => $validated['english_font'],
            'landing.layout' => $validated['landing_layout'] ?? 'default',
        ]);

        Setting::query()->where('key', 'typography.arabic_font')->delete();

        return back()->with('status', 'Appearance updated.');
    }
}
