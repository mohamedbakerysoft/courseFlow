<?php

namespace App\Actions\Dashboard\Courses;

use App\Models\Course;
use App\Models\User;

class CreateCourseAction
{
    public function execute(User $user, array $data): Course
    {
        $payload = [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'thumbnail_path' => $data['thumbnail_path'] ?? null,
            'price' => $data['price'] ?? 0,
            'currency' => $data['currency'] ?? 'USD',
            'is_free' => (bool) ($data['is_free'] ?? true),
            'status' => Course::STATUS_DRAFT,
            'language' => $data['language'] ?? 'en',
            'instructor_id' => $user->id,
        ];

        return Course::create($payload);
    }
}

