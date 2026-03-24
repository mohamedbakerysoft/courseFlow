<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private const HEADER_MENU_KEY = 'navigation.header_menu';

    public static function deleteByKeys(array $keys): void
    {
        static::query()
            ->whereIn('key', $keys)
            ->delete();
    }

    public static function ensureValue(string $key, string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function defaultHeaderMenuItems(): array
    {
        return [
            [
                'key' => 'home',
                'label' => 'Home',
                'route' => '/',
                'route_name' => null,
                'active' => ['*home*'],
                'is_enabled' => true,
                'sort_order' => 10,
            ],
            [
                'key' => 'courses',
                'label' => 'Courses',
                'route' => null,
                'route_name' => 'courses.index',
                'active' => ['courses.index', 'courses.show'],
                'is_enabled' => true,
                'sort_order' => 20,
            ],
            [
                'key' => 'books',
                'label' => 'Books',
                'route' => null,
                'route_name' => 'books.index',
                'active' => ['books.*'],
                'is_enabled' => true,
                'sort_order' => 30,
            ],
            [
                'key' => 'profile',
                'label' => 'My Profile',
                'route' => null,
                'route_name' => 'instructor.show',
                'active' => ['instructor.show'],
                'is_enabled' => true,
                'sort_order' => 40,
            ],
        ];
    }

    public static function headerMenuItems(): Collection
    {
        $saved = static::query()->where('key', self::HEADER_MENU_KEY)->value('value');
        $decoded = is_string($saved) ? json_decode($saved, true) : null;
        $defaults = collect(static::defaultHeaderMenuItems())->keyBy('key');

        if (! is_array($decoded)) {
            return $defaults->sortBy('sort_order')->values();
        }

        return collect($decoded)
            ->map(function ($item) use ($defaults) {
                $key = is_array($item) ? ($item['key'] ?? null) : null;
                $default = $key ? $defaults->get($key) : null;
                if (! $default) {
                    return null;
                }

                return [
                    'key' => $default['key'],
                    'label' => trim((string) ($item['label'] ?? $default['label'])) ?: $default['label'],
                    'route' => $default['route'],
                    'route_name' => $default['route_name'],
                    'active' => $default['active'],
                    'is_enabled' => filter_var($item['is_enabled'] ?? $default['is_enabled'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $default['is_enabled'],
                    'sort_order' => (int) ($item['sort_order'] ?? $default['sort_order']),
                ];
            })
            ->filter()
            ->sortBy('sort_order')
            ->values();
    }

    public static function saveHeaderMenuItems(array $items): void
    {
        $defaults = collect(static::defaultHeaderMenuItems())->keyBy('key');

        $payload = collect($items)
            ->map(function ($item, $index) use ($defaults) {
                $key = $item['key'] ?? null;
                $default = $key ? $defaults->get($key) : null;
                if (! $default) {
                    return null;
                }

                return [
                    'key' => $default['key'],
                    'label' => trim((string) ($item['label'] ?? $default['label'])) ?: $default['label'],
                    'is_enabled' => filter_var($item['is_enabled'] ?? $default['is_enabled'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $default['is_enabled'],
                    'sort_order' => ($index + 1) * 10,
                ];
            })
            ->filter()
            ->values()
            ->all();

        static::updateOrCreate(
            ['key' => self::HEADER_MENU_KEY],
            ['value' => json_encode($payload, JSON_UNESCAPED_UNICODE)]
        );
    }
}
