<?php

namespace App\Models;

use App\Support\MediaAsset;

class Course extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'download_file_path',
        'price',
        'currency',
        'is_free',
        'status',
        'product_type',
        'language',
        'instructor_id',
    ];

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_DRAFT = 'draft';

    public const TYPE_COURSE = 'course';

    public const TYPE_BOOK = 'book';

    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeCourses($query)
    {
        return $query->where('product_type', self::TYPE_COURSE);
    }

    public function scopeBooks($query)
    {
        return $query->where('product_type', self::TYPE_BOOK);
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

    public function getThumbnailUrlAttribute(): string
    {
        return MediaAsset::url($this->thumbnail_path, MediaAsset::courseFallbackPath($this->slug ?: $this->title));
    }

    public function getThumbnailFallbackUrlAttribute(): string
    {
        return MediaAsset::courseFallback($this->slug ?: $this->title);
    }

    public function getIsBookAttribute(): bool
    {
        return ($this->product_type ?? self::TYPE_COURSE) === self::TYPE_BOOK;
    }
}
