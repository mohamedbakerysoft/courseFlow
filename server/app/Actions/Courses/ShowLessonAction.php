<?php

namespace App\Actions\Courses;

use App\Models\Course;
use App\Models\Lesson;

class ShowLessonAction
{
    public function execute(Course $course, Lesson $lesson): Lesson
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        if ($lesson->status !== Lesson::STATUS_PUBLISHED) {
            abort(404);
        }

        return $lesson;
    }
}

