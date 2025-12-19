<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('draft course not visible publicly until published', function () {
    $instructor = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $course = Course::create([
        'title' => 'Draft Course',
        'slug' => 'draft-course',
        'status' => Course::STATUS_DRAFT,
        'language' => 'en',
        'instructor_id' => $instructor->id,
    ]);

    $response = \Pest\Laravel\get('/courses');
    $response->assertOk();
    $response->assertDontSee('Draft Course');

    $course->status = Course::STATUS_PUBLISHED;
    $course->save();

    $response = \Pest\Laravel\get('/courses');
    $response->assertOk();
    $response->assertSee('Draft Course');
});

