<?php

namespace App\Actions\Install;

use Illuminate\Support\Facades\Artisan;

class RunMigrationsAction
{
    public function execute(): array
    {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);

            return ['ok' => true];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
