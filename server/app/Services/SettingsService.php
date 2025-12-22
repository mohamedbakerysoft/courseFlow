<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected function cacheKey(): string
    {
        return 'settings.all';
    }

    public function all(): array
    {
        try {
            if (! app()->environment('production')) {
                return Setting::query()->pluck('value', 'key')->toArray();
            }

            return Cache::rememberForever($this->cacheKey(), function () {
                try {
                    return Setting::query()->pluck('value', 'key')->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public function set(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
        Cache::forget($this->cacheKey());
    }
}
