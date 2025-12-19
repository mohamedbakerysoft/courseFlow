<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function seedCourseLesson(): array {
    $course = Course::create([
        'title' => 'Course X',
        'slug' => 'course-x',
        'description' => 'Course',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    $lesson = Lesson::create([
        'course_id' => $course->id,
        'title' => 'Lesson 1',
        'slug' => 'lesson-1',
        'description' => 'First',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'position' => 1,
        'status' => Lesson::STATUS_PUBLISHED,
    ]);

    return [$course, $lesson];
}

it('guest cannot access lesson and is redirected to login', function () {
    [$course, $lesson] = seedCourseLesson();
    \Pest\Laravel\get("/courses/{$course->slug}/lessons/{$lesson->slug}")
        ->assertRedirect('/login');
});

it('non-enrolled user cannot access lesson', function () {
    [$course, $lesson] = seedCourseLesson();
    $user = User::factory()->create();
    \Pest\Laravel\actingAs($user)
        ->get("/courses/{$course->slug}/lessons/{$lesson->slug}")
        ->assertForbidden();
});

it('enrolled user can access published lesson', function () {
    [$course, $lesson] = seedCourseLesson();
    $user = User::factory()->create();
    $user->courses()->attach($course->id, ['enrolled_at' => now()]);

    \Pest\Laravel\actingAs($user)
        ->get("/courses/{$course->slug}/lessons/{$lesson->slug}")
        ->assertOk()
        ->assertSee('Lesson 1');
});

it('draft lesson returns 404', function () {
    $course = Course::create([
        'title' => 'Course Y',
        'slug' => 'course-y',
        'description' => 'Course',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    $lesson = Lesson::create([
        'course_id' => $course->id,
        'title' => 'Draft Lesson',
        'slug' => 'draft-lesson',
        'video_url' => 'https://player.vimeo.com/video/76979871',
        'position' => 1,
        'status' => Lesson::STATUS_DRAFT,
    ]);

    $user = User::factory()->create();
    $user->courses()->attach($course->id, ['enrolled_at' => now()]);

    \Pest\Laravel\actingAs($user)
        ->get("/courses/{$course->slug}/lessons/{$lesson->slug}")
        ->assertNotFound();
});

