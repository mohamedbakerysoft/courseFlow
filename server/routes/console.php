<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('demo:reseed-after-tests {--force-testing}', function () {
    $forceTesting = (bool) $this->option('force-testing');

    $databasePath = config('database.connections.sqlite.database');
    if ($databasePath && $databasePath !== ':memory:' && ! file_exists($databasePath)) {
        @touch($databasePath);
    }

    if ($forceTesting) {
        config()->set('demo.enabled', true);
        try {
            $sessionPath = storage_path('framework/sessions');
            if (is_dir($sessionPath)) {
                foreach (glob($sessionPath.'/*') as $file) {
                    @unlink($file);
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }
        $this->call('migrate', ['--force' => true]);
        $this->call(\Database\Seeders\DemoSeeder::class);

        return;
    }

    if (! app()->environment(['local', 'demo'])) {
        $this->info('Skipping demo reseed outside local/demo environment.');

        return;
    }

    $this->call('migrate', ['--force' => true]);
    $this->call('db:seed', ['--force' => true]);
})->purpose('Rebuild demo SQLite database after running the test suite');
