<?php

namespace App\Actions\Dashboard\Lessons;

use App\Models\Lesson;
use App\Models\Course;

class CreateLessonAction
{
    public function execute(Course $course, array $data): Lesson
    {
        return Lesson::create([
            'course_id' => $course->id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'video_url' => $data['video_url'],
            'position' => (int) $data['position'],
            'status' => Lesson::STATUS_PUBLISHED,
        ]);
    }
}

