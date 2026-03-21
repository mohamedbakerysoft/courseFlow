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

        return view('dashboard.appearance.edit', compact(
            'primary',
            'secondary',
            'accent',
        ));
    }

    public function update(Request $request, SettingsService $settings)
    {
        $validated = $request->validate([
            'primary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'secondary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'accent' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ]);
        $settings->set([
            'theme.primary' => $validated['primary'],
            'theme.secondary' => $validated['secondary'],
            'theme.accent' => $validated['accent'],
            'typography.english_font' => 'Poppins',
        ]);

        Setting::updateOrCreate(['key' => 'landing.layout'], ['value' => 'default']);

        Setting::query()->where('key', 'typography.arabic_font')->delete();

        return back()->with('status', 'Appearance updated.');
    }
}
