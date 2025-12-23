<?php

use App\Models\Course;
use App\Models\User;
use App\Support\MediaAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('falls back to bundled artwork when a course thumbnail path is invalid', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $course = Course::create([
        'title' => 'Fallback Course',
        'slug' => 'fallback-course',
        'description' => 'Fallback artwork check',
        'thumbnail_path' => 'images/demo/missing-course-cover.svg',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
        'instructor_id' => $admin->id,
    ]);

    $fallback = MediaAsset::courseFallback($course->slug);

    \Pest\Laravel\get(route('courses.show', $course))
        ->assertOk()
        ->assertSee($fallback, false);
});

it('falls back to bundled artwork when the instructor avatar path is invalid', function () {
    $admin = User::factory()->create([
        'name' => 'Avatar Admin',
        'role' => User::ROLE_ADMIN,
        'profile_image_path' => 'images/demo/missing-avatar.svg',
    ]);

    Course::create([
        'title' => 'Avatar Course',
        'slug' => 'avatar-course',
        'description' => 'Visible on instructor profile',
        'thumbnail_path' => 'images/demo/real/course-real-2.jpg',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
        'instructor_id' => $admin->id,
    ]);

    $fallback = MediaAsset::avatarFallback($admin->email);

    \Pest\Laravel\get('/instructor')
        ->assertOk()
        ->assertSee($fallback, false);
});

it('ships the bundled demo course covers and instructor avatars referenced by the UI', function () {
    foreach (range(1, 7) as $courseIndex) {
        expect(public_path('images/demo/real/course-real-'.$courseIndex.'.jpg'))->toBeFile();
    }

    foreach (range(1, 4) as $avatarIndex) {
        expect(public_path('images/demo/avatar-'.$avatarIndex.'.svg'))->toBeFile();
    }
});
