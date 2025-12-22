<?php

namespace App\Actions\Dashboard\Courses;

use App\Models\Course;

class DeleteCourseAction
{
    public function execute(Course $course): void
    {
        $course->delete();
    }
}
