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

    Course::create(['title' => 'C1', 'slug' => 'c1', 'status' => \App\Models\Course::STATUS_PUBLISHED, 'price' => 0, 'currency' => 'USD', 'is_free' => true, 'language' => 'en']);
    Course::create(['title' => 'C2', 'slug' => 'c2', 'status' => \App\Models\Course::STATUS_DRAFT, 'price' => 0, 'currency' => 'USD', 'is_free' => true, 'language' => 'en']);

    [$instructor, $courses, $links] = app(ShowInstructorProfileAction::class)->execute();

    expect($instructor->id)->toBe($admin->id);
    expect($courses)->toHaveCount(1);
    expect($courses->first()->title)->toBe('C1');
});
