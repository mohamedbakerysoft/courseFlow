<?php

namespace App\Actions\Updates;

use App\Services\SettingsService;

class DetectVersionAction
{
    public function execute(): array
    {
        $settings = app(SettingsService::class);

        $current = (string) $settings->get('app.version', '0.0.0');

        $new = (string) config('app.version', '');
        if ($new === '') {
            $new = $this->readVersionJson();
        }
        if ($new === '') {
            $new = '0.0.0';
        }

        $updateAvailable = version_compare($new, $current, '>');

        return [
            'current_version' => $current,
            'new_version' => $new,
            'update_available' => $updateAvailable,
            'status' => $updateAvailable ? 'update_available' : 'no_update',
        ];
    }

    protected function readVersionJson(): string
    {
        $paths = [
            base_path('version.json'),
            base_path('server/version.json'),
        ];

        foreach ($paths as $path) {
            try {
                if (is_file($path)) {
                    $json = json_decode((string) file_get_contents($path), true);
                    if (is_array($json) && isset($json['version']) && is_string($json['version'])) {
                        return $json['version'];
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        return '';
    }
}

