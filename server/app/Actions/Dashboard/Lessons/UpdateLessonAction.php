<?php

namespace App\Actions\Dashboard\Lessons;

use App\Models\Lesson;

class UpdateLessonAction
{
    public function execute(Lesson $lesson, array $data): Lesson
    {
        $lesson->fill([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'video_url' => $data['video_url'],
            'position' => (int) $data['position'],
        ])->save();

        return $lesson;
    }
}

