<?php

namespace App\Actions\Install;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class RunMigrationsAction
{
    public function execute(): array
    {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
            try {
                Artisan::call('storage:link');
            } catch (\Throwable $e) {
            }
            try {
                Storage::disk('public')->makeDirectory('logos');
            } catch (\Throwable $e) {
            }
            try {
                Storage::disk('public')->makeDirectory('landing');
            } catch (\Throwable $e) {
            }
            try {
                Storage::disk('public')->makeDirectory('hero');
            } catch (\Throwable $e) {
            }

            return ['ok' => true];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
