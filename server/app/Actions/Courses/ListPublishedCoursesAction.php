<?php

namespace App\Actions\Courses;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPublishedCoursesAction
{
    public function execute(int $perPage = Course::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return Course::paginatePublishedCourses($perPage);
    }
}
