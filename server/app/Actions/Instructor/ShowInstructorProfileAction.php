<?php

namespace App\Actions\Instructor;

use App\Models\Course;
use App\Models\User;

class ShowInstructorProfileAction
{
    public function execute(): array
    {
        $instructor = User::query()->where('role', User::ROLE_ADMIN)->firstOrFail();
        $courses = Course::query()->published()->select(['id', 'title', 'thumbnail_path'])->get();

        $links = [];
        if (!empty($instructor->social_links)) {
            $links = is_array($instructor->social_links)
                ? $instructor->social_links
                : json_decode($instructor->social_links, true) ?? [];
        }

        return [$instructor, $courses, $links];
    }
}
