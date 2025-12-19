<?php

namespace App\Actions\Courses;

use App\Models\Course;
use App\Models\User;

class CheckUserEnrollmentAction
{
    public function execute(?User $user, Course $course): bool
    {
        if (! $user) {
            return false;
        }

        return $user->courses()->where('course_id', $course->id)->exists();
    }
}

