<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function makeCourseWithLesson(string $courseSlug = 'course-p', string $lessonSlug = 'l-1', string $status = 'published'): array {
    $course = Course::create([
        'title' => 'Course P',
        'slug' => $courseSlug,
        'description' => 'Desc',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    $lesson = Lesson::create([
        'course_id' => $course->id,
        'title' => 'Lesson One',
        'slug' => $lessonSlug,
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'position' => 1,
        'status' => $status === 'published' ? Lesson::STATUS_PUBLISHED : Lesson::STATUS_DRAFT,
    ]);

    return [$course, $lesson];
}

it('creates progress when enrolled user opens lesson', function () {
    [$course, $lesson] = makeCourseWithLesson();
    $user = User::factory()->create();
    $user->courses()->attach($course->id, ['enrolled_at' => now()]);

    \Pest\Laravel\actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}")
        ->assertOk();

    $exists = DB::table('lesson_user_progress')
        ->where('user_id', $user->id)
        ->where('lesson_id', $lesson->id)
        ->exists();
    expect($exists)->toBeTrue();
});

it('opening the same lesson twice does not create duplicate progress', function () {
    [$course, $lesson] = makeCourseWithLesson('course-p2', 'l-1a');
    $user = User::factory()->create();
    $user->courses()->attach($course->id, ['enrolled_at' => now()]);

    \Pest\Laravel\actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}");
    \Pest\Laravel\actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}");

    $count = DB::table('lesson_user_progress')
        ->where('user_id', $user->id)
        ->where('lesson_id', $lesson->id)
        ->count();
    expect($count)->toBe(1);
});

it('draft lessons do not generate progress', function () {
    [$course, $lesson] = makeCourseWithLesson('course-p3', 'l-2', 'draft');
    $user = User::factory()->create();
    $user->courses()->attach($course->id, ['enrolled_at' => now()]);

    \Pest\Laravel\actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}")
        ->assertNotFound();

    $exists = DB::table('lesson_user_progress')
        ->where('user_id', $user->id)
        ->where('lesson_id', $lesson->id)
        ->exists();
    expect($exists)->toBeFalse();
});

it('course progress percentage is calculated correctly', function () {
    $course = Course::create([
        'title' => 'Course P4',
        'slug' => 'course-p4',
        'description' => 'Desc',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
    $lesson1 = Lesson::create([
        'course_id' => $course->id, 'title' => 'L1', 'slug' => 'l-1p4',
        'video_url' => 'https://player.vimeo.com/video/76979871', 'position' => 1, 'status' => Lesson::STATUS_PUBLISHED,
    ]);
    $lesson2 = Lesson::create([
        'course_id' => $course->id, 'title' => 'L2', 'slug' => 'l-2p4',
        'video_url' => 'https://player.vimeo.com/video/76979871', 'position' => 2, 'status' => Lesson::STATUS_PUBLISHED,
    ]);
    $lesson3 = Lesson::create([
        'course_id' => $course->id, 'title' => 'L3', 'slug' => 'l-3p4',
        'video_url' => 'https://player.vimeo.com/video/76979871', 'position' => 3, 'status' => Lesson::STATUS_PUBLISHED,
    ]);

    $user = User::factory()->create();
    $user->courses()->attach($course->id, ['enrolled_at' => now()]);

    \Pest\Laravel\actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson1->slug}");
    \Pest\Laravel\actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson2->slug}");

    \Pest\Laravel\actingAs($user)->get("/courses/{$course->slug}")
        ->assertOk()
        ->assertSee('Progress: 67%');
});

