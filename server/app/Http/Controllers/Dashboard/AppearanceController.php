<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    public function edit()
    {
        $primary = optional(Setting::where('key', 'theme.primary')->first())->value ?: '#4F46E5';
        $secondary = optional(Setting::where('key', 'theme.secondary')->first())->value ?: '#334155';
        $accent = optional(Setting::where('key', 'theme.accent')->first())->value ?: '#10B981';
        $arabicFont = optional(Setting::where('key', 'typography.arabic_font')->first())->value ?: 'Cairo';
        $englishFont = optional(Setting::where('key', 'typography.english_font')->first())->value ?: 'Inter';

        return view('dashboard.appearance.edit', compact('primary', 'secondary', 'accent', 'arabicFont', 'englishFont'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'secondary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'accent' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'arabic_font' => ['required', 'in:Cairo,Tajawal,IBM Plex Arabic'],
            'english_font' => ['required', 'in:Inter,Poppins,Roboto'],
        ]);
        foreach (['primary', 'secondary', 'accent'] as $k) {
            Setting::updateOrCreate(
                ['key' => "theme.$k"],
                ['value' => $validated[$k]]
            );
        }
        Setting::updateOrCreate(
            ['key' => 'typography.arabic_font'],
            ['value' => $validated['arabic_font']]
        );
        Setting::updateOrCreate(
            ['key' => 'typography.english_font'],
            ['value' => $validated['english_font']]
        );

        return back()->with('status', 'Appearance updated.');
    }
}
