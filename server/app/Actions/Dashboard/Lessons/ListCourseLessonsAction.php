<?php

namespace App\Actions\Dashboard\Lessons;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class ListCourseLessonsAction
{
    public function execute(Course $course): Collection
    {
        return $course->lessons()->get();
    }
}
