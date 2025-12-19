<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'video_url',
        'position',
        'status',
    ];

    public const STATUS_PUBLISHED = 'published';
    public const STATUS_DRAFT = 'draft';

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function completedByUsers()
    {
        return $this->belongsToMany(User::class, 'lesson_user_progress')
            ->withPivot('completed_at')
            ->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
