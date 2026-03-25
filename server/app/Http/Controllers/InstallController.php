<?php

namespace App\Http\Controllers;

use App\Actions\Install\CheckEnvironmentAction;
use App\Actions\Install\RunComposerInstallAction;
use App\Actions\Install\CreateAdminAction;
use App\Actions\Install\RunMigrationsAction;
use App\Actions\Install\BuildAssetsAction;
use App\Actions\Install\WriteEnvAction;
use App\Http\Requests\Installer\InstallAdminRequest;
use App\Http\Requests\Installer\InstallDatabaseRequest;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstallController extends Controller
{
    public function show(SettingsService $settings): View|RedirectResponse
    {
        $installed = (bool) $settings->get('app.installed', false);
        if ($installed) {
            return redirect()->to('/');
        }

        return view('installer.wizard');
    }

    public function check(CheckEnvironmentAction $action): JsonResponse
    {
        return response()->json($action->execute());
    }

    public function database(InstallDatabaseRequest $request, WriteEnvAction $action): JsonResponse
    {
        $path = $action->execute(
            (string) $request->input('app_url'),
            [
                'host' => (string) $request->input('host'),
                'port' => (int) $request->input('port'),
                'database' => (string) $request->input('database'),
                'username' => (string) $request->input('username'),
                'password' => (string) $request->input('password', ''),
            ]
        );

        return response()->json(['ok' => true, 'env_path' => $path]);
    }

    public function dependencies(RunComposerInstallAction $action): JsonResponse
    {
        return response()->json($action->execute());
    }

    public function build(BuildAssetsAction $action): JsonResponse
    {
        return response()->json($action->execute());
    }

    public function migrate(RunMigrationsAction $action): JsonResponse
    {
        return response()->json($action->execute());
    }

    public function admin(InstallAdminRequest $request, CreateAdminAction $action): JsonResponse
    {
        $user = $action->execute(
            (string) $request->input('name'),
            (string) $request->input('email'),
            (string) $request->input('password')
        );

        app(SettingsService::class)->set(['app.installed' => true]);

        return response()->json(['ok' => true, 'user_id' => $user->id]);
    }

    public function finish(): RedirectResponse
    {
        return redirect()->to('/login');
    }
}
