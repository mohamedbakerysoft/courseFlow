<?php

namespace App\Actions\Dashboard\Lessons;

use App\Models\Lesson;

class DeleteLessonAction
{
    public function execute(Lesson $lesson): void
    {
        $lesson->delete();
    }
}

