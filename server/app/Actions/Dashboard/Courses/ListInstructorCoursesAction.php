<?php

namespace App\Actions\Dashboard\Courses;

use App\Models\User;
use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListInstructorCoursesAction
{
    public function execute(User $user, int $perPage = 12): LengthAwarePaginator
    {
        return Course::where('instructor_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}

