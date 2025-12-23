<?php

namespace App\Actions\Dashboard\Courses;

use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListInstructorCoursesAction
{
    public function execute(User $user, int $perPage = 12): LengthAwarePaginator
    {
        return Course::where('instructor_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function executeByType(User $user, string $productType, int $perPage = 12): LengthAwarePaginator
    {
        return Course::where('instructor_id', $user->id)
            ->where('product_type', $productType)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
