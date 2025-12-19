<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function create(User $user, Lesson $lesson): bool
    {
        return $lesson->course && $lesson->course->instructor_id === $user->id;
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $lesson->course && $lesson->course->instructor_id === $user->id;
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $lesson->course && $lesson->course->instructor_id === $user->id;
    }
}

