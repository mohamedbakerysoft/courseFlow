<?php

namespace App\Actions\Courses;

use App\Models\Course;

class ShowCourseAction
{
    public function execute(Course $course): Course
    {
        if ($course->status !== Course::STATUS_PUBLISHED) {
            abort(404);
        }

        return $course;
    }
}
