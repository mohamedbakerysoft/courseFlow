<?php

namespace App\Models;

class Course extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'price',
        'currency',
        'is_free',
        'status',
        'language',
    ];

    public const STATUS_PUBLISHED = 'published';
    public const STATUS_DRAFT = 'draft';

    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
