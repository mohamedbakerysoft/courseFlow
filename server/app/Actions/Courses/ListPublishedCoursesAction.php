<?php

namespace App\Actions\Courses;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPublishedCoursesAction
{
    public function execute(?string $priceFilter = null, int $perPage = Course::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return Course::paginatePublishedCourses($priceFilter, $perPage);
    }
}
