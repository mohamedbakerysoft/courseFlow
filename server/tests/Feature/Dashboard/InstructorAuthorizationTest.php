<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('instructor cannot access others courses', function () {
    $instructorA = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $instructorB = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $courseB = Course::create([
        'title' => 'B Course',
        'slug' => 'b-course',
        'status' => Course::STATUS_DRAFT,
        'language' => 'en',
        'instructor_id' => $instructorB->id,
    ]);

    $this->actingAs($instructorA);

    $this->get(route('dashboard.courses.edit', $courseB))->assertForbidden();
    $this->put(route('dashboard.courses.update', $courseB), [
        'title' => 'xx',
        'slug' => 'b-course',
        'language' => 'en',
    ])->assertForbidden();
    $this->post(route('dashboard.courses.publish', $courseB))->assertForbidden();
});
