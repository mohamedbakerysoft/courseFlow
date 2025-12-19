<?php

use App\Actions\Instructor\ShowInstructorProfileAction;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns admin instructor and published courses', function () {
    $admin = User::factory()->create([
        'name' => 'Admin X',
        'role' => User::ROLE_ADMIN,
    ]);

    Course::create(['title' => 'C1', 'status' => \App\Models\Course::STATUS_PUBLISHED]);
    Course::create(['title' => 'C2', 'status' => \App\Models\Course::STATUS_DRAFT]);

    [$instructor, $courses, $links] = app(ShowInstructorProfileAction::class)->execute();

    expect($instructor->id)->toBe($admin->id);
    expect($courses)->toHaveCount(1);
    expect($courses->first()->title)->toBe('C1');
});

