<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model
{
    protected $fillable = [
        'question',
        'answer',
        'is_visible',
        'sort_order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public static function publicList(): Collection
    {
        return static::query()
            ->visible()
            ->ordered()
            ->get(['id', 'question', 'answer']);
    }

    public static function adminList(): Collection
    {
        return static::query()
            ->ordered()
            ->get(['id', 'question', 'answer', 'is_visible', 'sort_order']);
    }
}
