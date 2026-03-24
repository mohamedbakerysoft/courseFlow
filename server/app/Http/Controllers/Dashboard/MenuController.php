<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Menus\ReorderMenuRequest;
use App\Http\Requests\Dashboard\Menus\UpdateMenuRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function edit(): View
    {
        $menuItems = Setting::headerMenuItems();

        return view('dashboard.menus.edit', compact('menuItems'));
    }

    public function update(UpdateMenuRequest $request): RedirectResponse
    {
        Setting::saveHeaderMenuItems($request->validated('items'));

        return back()->with('status', 'Menu updated.');
    }

    public function reorder(ReorderMenuRequest $request): JsonResponse
    {
        $current = Setting::headerMenuItems()->keyBy('key');
        $items = collect($request->validated('item_keys'))
            ->map(fn ($key) => $current->get($key))
            ->filter()
            ->values()
            ->all();

        Setting::saveHeaderMenuItems($items);

        return response()->json(['status' => 'ok']);
    }
}
