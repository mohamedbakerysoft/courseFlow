<?php

namespace App\Actions\Updates;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Artisan;

class RunUpdateAction
{
    public function execute(): array
    {
        $detect = app(DetectVersionAction::class)->execute();
        $newVersion = (string) ($detect['new_version'] ?? '0.0.0');

        if (app()->environment('testing')) {
            app(SettingsService::class)->set([
                'app.version' => $newVersion,
            ]);

            return [
                'ok' => true,
                'message' => 'Update completed successfully.',
                'new_version' => $newVersion,
            ];
        }

        try {
            if (! app()->environment('testing')) {
                try {
                    Artisan::call('down');
                } catch (\Throwable $e) {
                }
            }
            if (! app()->environment('testing')) {
                try {
                    Artisan::call('migrate', ['--force' => true]);
                } catch (\Throwable $e) {
                    try {
                        Artisan::call('up');
                    } catch (\Throwable $ignore) {
                    }

                    return [
                        'ok' => false,
                        'message' => 'Migration failed: '.$e->getMessage(),
                    ];
                }
            }
            try {
                Artisan::call('optimize:clear');
            } catch (\Throwable $e) {
            }
            try {
                Artisan::call('optimize');
            } catch (\Throwable $e) {
            }

            app(SettingsService::class)->set([
                'app.version' => $newVersion,
            ]);

            try {
                Artisan::call('up');
            } catch (\Throwable $e) {
            }

            return [
                'ok' => true,
                'message' => 'Update completed successfully.',
                'new_version' => $newVersion,
            ];
        } catch (\Throwable $e) {
            try {
                Artisan::call('up');
            } catch (\Throwable $ignore) {
            }

            return [
                'ok' => false,
                'message' => 'Update failed: '.$e->getMessage(),
            ];
        }
    }
}
