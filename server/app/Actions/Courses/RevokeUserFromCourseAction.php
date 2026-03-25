<?php

namespace App\Actions\Courses;

use App\Models\Course;
use App\Models\User;

class RevokeUserFromCourseAction
{
    public function execute(User $user, Course $course): void
    {
        if (! $user->courses()->where('course_id', $course->id)->exists()) {
            return;
        }

        $user->courses()->detach($course->id);
    }
}
