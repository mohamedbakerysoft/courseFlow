<?php

namespace App\Actions\Install;

class RunComposerInstallAction
{
    public function execute(): array
    {
        if (app()->environment('testing')) {
            return ['ok' => true, 'message' => 'testing'];
        }

        $autoload = base_path('vendor/autoload.php');
        if (is_file($autoload)) {
            return ['ok' => true, 'message' => 'vendor_present'];
        }

        try {
            $cmd = 'composer install --no-dev --no-interaction --no-progress --prefer-dist';
            $proc = proc_open($cmd, [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ], $pipes, base_path());
            if (is_resource($proc)) {
                stream_get_contents($pipes[1] ?? null);
                stream_get_contents($pipes[2] ?? null);
                foreach ($pipes as $p) {
                    if (is_resource($p)) {
                        @fclose($p);
                    }
                }
                $code = proc_close($proc);
                if ($code !== 0) {
                    return ['ok' => false, 'message' => 'composer_failed'];
                }
            }
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'composer_error'];
        }

        if (! is_file($autoload)) {
            return ['ok' => false, 'message' => 'autoload_missing'];
        }

        return ['ok' => true, 'message' => 'composer_done'];
    }
}
