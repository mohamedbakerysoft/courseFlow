<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows a student dashboard without admin actions', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Student Course',
        'slug' => 'student-course',
        'description' => 'Course for enrolled learners.',
        'price' => 49,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
    $student->courses()->attach($course->id, ['enrolled_at' => now()]);

    $response = $this->actingAs($student)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Student dashboard');
    $response->assertSee('Browse Courses');
    $response->assertSee('Browse Books');
    $response->assertSee('Student Course');
    $response->assertDontSee('Admin workspace');
    $response->assertDontSee('Create course');
    $response->assertDontSee('Review manual payments');
});

it('keeps students blocked from admin dashboard pages', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

    $this->actingAs($student)
        ->get(route('dashboard.courses.index'))
        ->assertForbidden();
});
