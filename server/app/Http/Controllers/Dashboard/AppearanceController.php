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
        return view('dashboard.appearance.edit', compact('primary', 'secondary', 'accent'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'primary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'secondary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'accent' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ]);
        foreach (['primary', 'secondary', 'accent'] as $k) {
            Setting::updateOrCreate(
                ['key' => "theme.$k"],
                ['value' => $validated[$k]]
            );
        }
        return back()->with('status', 'Theme updated.');
    }
}

