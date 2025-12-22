<?php

namespace App\Actions\Progress;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;

class CalculateCourseProgressAction
{
    public function execute(User $user, Course $course): int
    {
        $total = $course->lessons()->where('status', Lesson::STATUS_PUBLISHED)->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $user->completedLessons()
            ->where('lessons.course_id', $course->id)
            ->where('lessons.status', Lesson::STATUS_PUBLISHED)
            ->count();

        return (int) round(($completed / $total) * 100);
    }
}
