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
        $primary = optional(Setting::where('key', 'theme.primary')->first())->value ?: '#3A5BA9';
        $secondary = optional(Setting::where('key', 'theme.secondary')->first())->value ?: '#2F3C4F';
        $accent = optional(Setting::where('key', 'theme.accent')->first())->value ?: '#0FA3A4';
        $arabicFont = optional(Setting::where('key', 'typography.arabic_font')->first())->value ?: 'Cairo';
        $englishFont = optional(Setting::where('key', 'typography.english_font')->first())->value ?: 'Inter';

        $heroImagePath = (string) $settings->get('hero.image_path', (string) $settings->get('landing.instructor_image', ''));
        $heroImageUrl = $heroImagePath !== '' ? asset('storage/'.$heroImagePath) : null;
        $heroImageFit = (string) $settings->get('hero.image_fit', 'contain');
        $heroImageFocus = (string) $settings->get('hero.image_focus', 'center');
        $heroImageRatio = (string) $settings->get('hero.image_ratio', '16:9');
        $landingLayout = (string) $settings->get('landing.layout', 'default');

        return view('dashboard.appearance.edit', compact(
            'primary',
            'secondary',
            'accent',
            'arabicFont',
            'englishFont',
            'heroImageUrl',
            'heroImageFit',
            'heroImageFocus',
            'heroImageRatio',
            'landingLayout',
        ));
    }

    public function update(Request $request, SettingsService $settings)
    {
        $validated = $request->validate([
            'primary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'secondary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'accent' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'arabic_font' => ['required', 'in:Cairo,Tajawal,IBM Plex Arabic'],
            'english_font' => ['required', 'in:Inter,Poppins,Roboto'],
            'hero_image' => ['nullable', 'image', 'max:4096'],
            'hero_image_fit' => ['nullable', 'in:contain,cover'],
            'hero_image_focus' => ['nullable', 'in:center,top,bottom,left,right'],
            'hero_image_ratio' => ['nullable', 'in:16:9,4:5,1:1'],
            'landing_layout' => ['nullable', 'in:default,layout_v2,layout_v3'],
        ]);
        $settings->set([
            'theme.primary' => $validated['primary'],
            'theme.secondary' => $validated['secondary'],
            'theme.accent' => $validated['accent'],
            'typography.arabic_font' => $validated['arabic_font'],
            'typography.english_font' => $validated['english_font'],
            'hero.image_fit' => $validated['hero_image_fit'] ?? 'contain',
            'hero.image_focus' => $validated['hero_image_focus'] ?? 'center',
            'hero.image_ratio' => $validated['hero_image_ratio'] ?? '16:9',
            'landing.layout' => $validated['landing_layout'] ?? 'default',
        ]);

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('landing', 'public');
            $settings->set(['hero.image_path' => $path]);
        }

        return back()->with('status', 'Appearance updated.');
    }
}
