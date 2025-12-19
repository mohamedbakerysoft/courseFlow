<?php

namespace App\Models;

use App\Models\Lesson;

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
        'instructor_id',
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

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
