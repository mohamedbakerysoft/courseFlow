<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    public function view(User $user, Course $course): bool
    {
        return $course->instructor_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    public function update(User $user, Course $course): bool
    {
        return $course->instructor_id === $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $course->instructor_id === $user->id;
    }

    public function publish(User $user, Course $course): bool
    {
        return $course->instructor_id === $user->id;
    }
}

