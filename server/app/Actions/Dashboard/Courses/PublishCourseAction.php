<?php

namespace App\Actions\Dashboard\Courses;

use App\Models\Course;

class PublishCourseAction
{
    public function execute(Course $course): Course
    {
        $course->status = Course::STATUS_PUBLISHED;
        $course->save();
        return $course;
    }
}

