<?php

namespace App\Actions\Install;

use Illuminate\Support\Facades\Artisan;

class RunMigrationsAction
{
    public function execute(): array
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);

        return ['ok' => true];
    }
}
