<?php

namespace App\Actions\Progress;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Carbon;

class MarkLessonCompletedAction
{
    public function execute(User $user, Lesson $lesson): bool
    {
        if ($lesson->status !== Lesson::STATUS_PUBLISHED) {
            return false;
        }

        $progress = LessonProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['completed_at' => Carbon::now()]
        );

        return $progress->wasRecentlyCreated === true || ! is_null($progress->completed_at);
    }
}

