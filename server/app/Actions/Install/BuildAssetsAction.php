<?php

namespace App\Actions\Install;

class BuildAssetsAction
{
    public function execute(): array
    {
        if (app()->environment('testing')) {
            return ['ok' => true, 'message' => 'testing'];
        }

        $manifest = public_path('build/manifest.json');
        if (is_file($manifest)) {
            return ['ok' => true, 'message' => 'assets_present'];
        }

        try {
            $cmd = 'npm run build';
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
                    return ['ok' => false, 'message' => 'build_failed'];
                }
            }
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'build_error'];
        }

        if (! is_file($manifest)) {
            return ['ok' => false, 'message' => 'manifest_missing'];
        }

        return ['ok' => true, 'message' => 'build_done'];
    }
}
