<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'slug',
        'description',
        'video_url',
        'video_file_path',
        'position',
        'status',
    ];

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_DRAFT = 'draft';

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
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

    public function getVideoFileUrlAttribute(): ?string
    {
        if (! filled($this->video_file_path)) {
            return null;
        }

        return asset('storage/'.$this->video_file_path);
    }

    public static function nextPositionForModule(?int $moduleId, int $courseId): int
    {
        return ((int) static::query()
            ->where('course_id', $courseId)
            ->where('module_id', $moduleId)
            ->max('position')) + 1;
    }

    public static function reorderForCourse(Course $course, array $moduleLessonsMap): void
    {
        DB::transaction(function () use ($course, $moduleLessonsMap) {
            $courseLessonIds = static::query()
                ->where('course_id', $course->id)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            foreach ($moduleLessonsMap as $moduleEntry) {
                $moduleId = isset($moduleEntry['module_id']) ? (int) $moduleEntry['module_id'] : null;
                $lessonIds = collect($moduleEntry['lesson_ids'] ?? [])
                    ->map(fn ($id) => (int) $id)
                    ->filter(fn ($id) => in_array($id, $courseLessonIds, true))
                    ->values();

                foreach ($lessonIds as $index => $lessonId) {
                    static::query()
                        ->whereKey($lessonId)
                        ->update([
                            'module_id' => $moduleId,
                            'position' => $index + 1,
                        ]);
                }
            }
        });
    }

    public static function countForCourses(array $courseIds): int
    {
        if ($courseIds === []) {
            return 0;
        }

        return (int) static::query()
            ->whereIn('course_id', $courseIds)
            ->count();
    }

    public static function latestDraftForCourses(array $courseIds): ?self
    {
        if ($courseIds === []) {
            return null;
        }

        return static::query()
            ->whereIn('course_id', $courseIds)
            ->where('status', self::STATUS_DRAFT)
            ->with('course')
            ->latest('updated_at')
            ->first();
    }

    public static function recentForCourses(array $courseIds, int $limit = 5): Collection
    {
        if ($courseIds === []) {
            return collect();
        }

        return static::query()
            ->whereIn('course_id', $courseIds)
            ->with('course')
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get(['id', 'course_id', 'slug', 'title', 'status', 'updated_at']);
    }
}
