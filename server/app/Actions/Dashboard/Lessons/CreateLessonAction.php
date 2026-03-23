<?php

namespace App\Actions\Dashboard\Lessons;

use App\Models\Course;
use App\Models\Lesson;

class CreateLessonAction
{
    public function execute(Course $course, array $data): Lesson
    {
        return Lesson::create([
            'course_id' => $course->id,
            'module_id' => $data['module_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'video_url' => $data['video_url'] ?? null,
            'video_file_path' => $data['video_file_path'] ?? null,
            'position' => Lesson::nextPositionForModule((int) $data['module_id'], $course->id),
            'status' => Lesson::STATUS_PUBLISHED,
        ]);
    }
}
