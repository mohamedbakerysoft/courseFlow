<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function makeCourse(): Course {
    return Course::create([
        'title' => 'Free Course',
        'slug' => 'free-course',
        'description' => 'Desc',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
}

it('guest cannot enroll and is redirected to login', function () {
    $course = makeCourse();
    \Pest\Laravel\post("/courses/{$course->slug}/enroll")
        ->assertRedirect('/login');
});

it('authenticated user can enroll in free course', function () {
    $course = makeCourse();
    $user = User::factory()->create();
    \Pest\Laravel\actingAs($user)
        ->post("/courses/{$course->slug}/enroll")
        ->assertRedirect("/courses/{$course->slug}");

    expect(DB::table('enrollments')->where('user_id', $user->id)->where('course_id', $course->id)->exists())->toBeTrue();
});

it('user cannot enroll twice', function () {
    $course = makeCourse();
    $user = User::factory()->create();
    \Pest\Laravel\actingAs($user)->post("/courses/{$course->slug}/enroll");
    \Pest\Laravel\actingAs($user)->post("/courses/{$course->slug}/enroll");

    $count = DB::table('enrollments')->where('user_id', $user->id)->where('course_id', $course->id)->count();
    expect($count)->toBe(1);
});

it('enrolled user sees correct UI state', function () {
    $course = makeCourse();
    $user = User::factory()->create();
    \Pest\Laravel\actingAs($user)->post("/courses/{$course->slug}/enroll");

    \Pest\Laravel\actingAs($user)->get("/courses/{$course->slug}")
        ->assertOk()
        ->assertSee('You are enrolled');
});
