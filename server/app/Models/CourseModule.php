<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CourseModule extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'position',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'module_id')->orderBy('position');
    }

    public static function nextPositionForCourse(Course $course): int
    {
        return ((int) $course->modules()->max('position')) + 1;
    }

    public static function reorderForCourse(Course $course, array $orderedModuleIds): void
    {
        DB::transaction(function () use ($course, $orderedModuleIds) {
            $modules = $course->modules()
                ->orderBy('position')
                ->get(['id']);

            $existingIds = $modules->pluck('id')->map(fn ($id) => (int) $id)->all();
            $sortedIds = collect($orderedModuleIds)
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => in_array($id, $existingIds, true))
                ->values();

            $missingIds = collect($existingIds)
                ->reject(fn ($id) => $sortedIds->contains($id))
                ->values();

            $finalIds = $sortedIds->concat($missingIds)->values();

            foreach ($finalIds as $index => $moduleId) {
                static::query()
                    ->whereKey($moduleId)
                    ->update(['position' => $index + 1]);
            }
        });
    }

    public static function normalizePositionsForCourse(Course $course): void
    {
        static::reorderForCourse(
            $course,
            $course->modules()->orderBy('position')->pluck('id')->all()
        );
    }
}
