<?php

namespace App\Actions\Courses;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPublishedCoursesAction
{
    public function execute(int $perPage = 12): LengthAwarePaginator
    {
        return Course::published()
            ->orderByDesc('created_at')
            ->select(['id', 'slug', 'title', 'description', 'thumbnail_path', 'price', 'currency', 'is_free'])
            ->paginate($perPage);
    }
}

