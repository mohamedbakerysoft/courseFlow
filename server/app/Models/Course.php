<?php

namespace App\Models;

class Course extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'title',
        'thumbnail_path',
        'status',
    ];

    public const STATUS_PUBLISHED = 'published';
    public const STATUS_DRAFT = 'draft';

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }
}
