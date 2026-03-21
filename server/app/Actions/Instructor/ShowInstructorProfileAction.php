<?php

namespace App\Actions\Instructor;

use App\Models\Course;
use App\Models\User;

class ShowInstructorProfileAction
{
    public function execute(): array
    {
        $instructor = User::primaryInstructorOrFail();
        $courses = Course::listPublishedForInstructorProfile();

        $links = [];
        if (! empty($instructor->social_links)) {
            $links = is_array($instructor->social_links)
                ? $instructor->social_links
                : json_decode($instructor->social_links, true) ?? [];
        }

        return [$instructor, $courses, $links];
    }
}
