<?php

namespace App\Actions\Instructor;

use App\Models\Course;
use App\Models\User;

class ShowInstructorProfileAction
{
    public function execute(): array
    {
        $instructor = User::query()
            ->where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))
            ->first()
            ?: User::query()->where('role', User::ROLE_ADMIN)->firstOrFail();
        $courses = Course::query()
            ->published()
            ->with('instructor')
            ->select(['id', 'slug', 'title', 'description', 'thumbnail_path', 'price', 'currency', 'is_free', 'language', 'instructor_id'])
            ->get();

        $links = [];
        if (! empty($instructor->social_links)) {
            $links = is_array($instructor->social_links)
                ? $instructor->social_links
                : json_decode($instructor->social_links, true) ?? [];
        }

        return [$instructor, $courses, $links];
    }
}
