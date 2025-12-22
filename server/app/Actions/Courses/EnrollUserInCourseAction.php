<?php

namespace App\Actions\Courses;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Carbon;

class EnrollUserInCourseAction
{
    public function execute(User $user, Course $course): void
    {
        if ($user->courses()->where('course_id', $course->id)->exists()) {
            return;
        }

        $user->courses()->attach($course->id, [
            'enrolled_at' => Carbon::now(),
        ]);
    }
}
