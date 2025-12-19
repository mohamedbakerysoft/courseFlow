<?php

namespace App\Actions\Courses;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPublishedCoursesAction
{
    public function execute(int $perPage = 12): LengthAwarePaginator
    {
        return Course::published()
            ->with('instructor')
            ->orderByDesc('created_at')
            ->select(['id', 'slug', 'title', 'description', 'thumbnail_path', 'price', 'currency', 'is_free', 'instructor_id'])
            ->paginate($perPage);
    }
}
