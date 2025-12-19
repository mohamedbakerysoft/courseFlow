<?php

namespace App\Actions\Courses;

use App\Models\Course;
use Illuminate\Http\Exceptions\HttpResponseException;

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

