<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ReferenceOption extends Model
{
    protected $fillable = [
        'type',
        'code',
        'label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const TYPE_LANGUAGE = 'language';

    public const TYPE_CURRENCY = 'currency';

    protected const FALLBACK_OPTIONS = [
        self::TYPE_LANGUAGE => [
            ['code' => 'en', 'label' => 'English'],
            ['code' => 'ar', 'label' => 'Arabic'],
            ['code' => 'fr', 'label' => 'French'],
        ],
        self::TYPE_CURRENCY => [
            ['code' => 'USD', 'label' => 'US Dollar (USD)'],
            ['code' => 'EUR', 'label' => 'Euro (EUR)'],
            ['code' => 'GBP', 'label' => 'British Pound (GBP)'],
            ['code' => 'EGP', 'label' => 'Egyptian Pound (EGP)'],
            ['code' => 'SAR', 'label' => 'Saudi Riyal (SAR)'],
            ['code' => 'AED', 'label' => 'UAE Dirham (AED)'],
        ],
    ];

    public static function activeByType(string $type): Collection
    {
        if (! Schema::hasTable((new static)->getTable())) {
            return static::fallbackOptions($type);
        }

        $options = static::query()
            ->where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get(['code', 'label']);

        return $options->isNotEmpty() ? $options : static::fallbackOptions($type);
    }

    public static function languageOptions(): Collection
    {
        return static::activeByType(static::TYPE_LANGUAGE);
    }

    public static function currencyOptions(): Collection
    {
        return static::activeByType(static::TYPE_CURRENCY);
    }

    protected static function fallbackOptions(string $type): Collection
    {
        return static::hydrate(static::FALLBACK_OPTIONS[$type] ?? []);
    }
}
