<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaAsset
{
    public static function url(?string $path, string $fallback): string
    {
        $fallbackPath = ltrim($fallback, '/');
        $fallbackUrl = asset($fallbackPath);
        $source = trim((string) $path);

        if ($source === '') {
            return $fallbackUrl;
        }

        if (Str::startsWith($source, ['http://', 'https://', '//'])) {
            return $source;
        }

        $normalized = ltrim($source, '/');

        if (file_exists(public_path($normalized))) {
            return asset($normalized);
        }

        if (Str::startsWith($normalized, 'storage/')) {
            $storagePath = Str::after($normalized, 'storage/');

            if ($storagePath !== '' && Storage::disk('public')->exists($storagePath)) {
                return asset($normalized);
            }

            return $fallbackUrl;
        }

        if (Storage::disk('public')->exists($normalized)) {
            return asset('storage/'.$normalized);
        }

        return $fallbackUrl;
    }

    public static function courseFallback(?string $seed = null): string
    {
        return asset(self::courseFallbackPath($seed));
    }

    public static function courseFallbackPath(?string $seed = null): string
    {
        return self::pick([
            'images/demo/real/course-real-1.jpg',
            'images/demo/real/course-real-2.jpg',
            'images/demo/real/course-real-3.jpg',
            'images/demo/real/course-real-4.jpg',
            'images/demo/real/course-real-5.jpg',
            'images/demo/real/course-real-6.jpg',
            'images/demo/real/course-real-7.jpg',
        ], $seed);
    }

    public static function avatarFallback(?string $seed = null): string
    {
        return asset(self::avatarFallbackPath($seed));
    }

    public static function avatarFallbackPath(?string $seed = null): string
    {
        return self::pick([
            'images/demo/avatar-1.svg',
            'images/demo/avatar-2.svg',
            'images/demo/avatar-3.svg',
            'images/demo/avatar-4.svg',
        ], $seed);
    }

    protected static function pick(array $items, ?string $seed): string
    {
        if ($seed === null || $seed === '') {
            return $items[0];
        }

        $index = abs(crc32($seed)) % count($items);

        return $items[$index];
    }
}
