<?php

namespace App\Actions\Dashboard\Courses;

use App\Models\Course;

class UnpublishCourseAction
{
    public function execute(Course $course): Course
    {
        $course->status = Course::STATUS_DRAFT;
        $course->save();
        return $course;
    }
}

