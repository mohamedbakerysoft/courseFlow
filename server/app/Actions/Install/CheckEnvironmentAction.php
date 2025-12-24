<?php

namespace App\Actions\Install;

class CheckEnvironmentAction
{
    public function execute(): array
    {
        $versionOk = version_compare(PHP_VERSION, '8.1.0', '>=');
        $extensions = [
            'mbstring',
            'openssl',
            'pdo',
            'pdo_mysql',
            'curl',
            'dom',
            'fileinfo',
        ];
        $missing = [];
        foreach ($extensions as $ext) {
            if (! extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }
        $writable = [
            base_path('storage'),
            base_path('bootstrap/cache'),
        ];
        $notWritable = [];
        foreach ($writable as $path) {
            if (! is_writable($path)) {
                $notWritable[] = $path;
            }
        }

        $ok = $versionOk && empty($missing) && empty($notWritable);

        return [
            'ok' => $ok,
            'php_version' => PHP_VERSION,
            'version_ok' => $versionOk,
            'missing_extensions' => $missing,
            'not_writable' => $notWritable,
        ];
    }
}
