<?php

namespace App\Actions\Dashboard\Lessons;

use App\Models\Lesson;

class UpdateLessonAction
{
    public function execute(Lesson $lesson, array $data): Lesson
    {
        $moduleId = (int) $data['module_id'];
        $position = $lesson->module_id === $moduleId
            ? $lesson->position
            : Lesson::nextPositionForModule($moduleId, $lesson->course_id);

        $lesson->fill([
            'module_id' => $moduleId,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'video_url' => $data['video_url'] ?? null,
            'video_file_path' => $data['video_file_path'] ?? $lesson->video_file_path,
            'position' => $position,
        ])->save();

        return $lesson;
    }
}
