<?php

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Actions\Dashboard\Users\GrantCourseAccessAction;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('grants course access using enroll action', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Grant Action Course',
        'slug' => 'grant-action-course',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
        'instructor_id' => $admin->id,
    ]);

    $grant = new GrantCourseAccessAction(new EnrollUserInCourseAction);
    $grant->execute($student, $course);

    $exists = $student->courses()->where('course_id', $course->id)->exists();
    expect($exists)->toBeTrue();
});
