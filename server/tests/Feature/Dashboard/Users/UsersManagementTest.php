<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('admin can view users list and user details', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT, 'name' => 'Student One']);

    \Pest\Laravel\actingAs($admin)
        ->get(route('dashboard.users.index'))
        ->assertOk()
        ->assertSee('Users')
        ->assertSee('Student One');

    \Pest\Laravel\actingAs($admin)
        ->get(route('dashboard.users.show', $student))
        ->assertOk()
        ->assertSee('User Details')
        ->assertSee('Student One');
});

it('admin can deactivate and activate a user', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT, 'is_disabled' => false]);

    \Pest\Laravel\actingAs($admin)
        ->post(route('dashboard.users.status', $student), ['is_disabled' => true])
        ->assertRedirect();

    expect($student->fresh()->is_disabled)->toBeTrue();

    \Pest\Laravel\actingAs($admin)
        ->post(route('dashboard.users.status', $student), ['is_disabled' => false])
        ->assertRedirect();

    expect($student->fresh()->is_disabled)->toBeFalse();
});

it('admin can grant course access', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Grantable Course',
        'slug' => 'grantable-course',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
        'instructor_id' => $admin->id,
    ]);

    \Pest\Laravel\actingAs($admin)
        ->post(route('dashboard.users.grant_access', $student), ['course_id' => $course->id])
        ->assertRedirect();

    $exists = $student->courses()->where('course_id', $course->id)->exists();
    expect($exists)->toBeTrue();
});
