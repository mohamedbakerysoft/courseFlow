<?php

namespace App\Actions\Dashboard\Courses;

use App\Models\Course;

class UpdateCourseAction
{
    public function execute(Course $course, array $data): Course
    {
        $course->fill([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'thumbnail_path' => $data['thumbnail_path'] ?? null,
            'download_file_path' => $data['download_file_path'] ?? $course->download_file_path,
            'price' => $data['price'] ?? 0,
            'currency' => $data['currency'] ?? 'USD',
            'is_free' => (bool) ($data['is_free'] ?? true),
            'product_type' => $data['product_type'] ?? $course->product_type ?? Course::TYPE_COURSE,
            'language' => $data['language'] ?? 'en',
        ])->save();

        return $course;
    }
}
