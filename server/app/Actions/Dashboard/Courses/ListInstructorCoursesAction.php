<?php

namespace App\Actions\Dashboard\Courses;

use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListInstructorCoursesAction
{
    public function execute(User $user, int $perPage = Course::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return Course::paginateInstructorItems($user, null, $perPage);
    }

    public function executeByType(User $user, string $productType, int $perPage = Course::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return Course::paginateInstructorItems($user, $productType, $perPage);
    }
}
