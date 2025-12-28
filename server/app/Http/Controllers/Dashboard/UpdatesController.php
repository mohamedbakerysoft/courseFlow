<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Updates\DetectVersionAction;
use App\Actions\Updates\RunUpdateAction;
use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UpdatesController extends Controller
{
    public function edit(SettingsService $settings): View
    {
        $current = (string) $settings->get('app.version', '0.0.0');
        return view('dashboard.settings.updates', [
            'currentVersion' => $current,
        ]);
    }

    public function detect(DetectVersionAction $action): JsonResponse
    {
        return response()->json($action->execute());
    }

    public function run(RunUpdateAction $action): JsonResponse
    {
        return response()->json($action->execute());
    }
}

