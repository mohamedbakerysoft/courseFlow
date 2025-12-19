<?php

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('instructor can create edit delete own course', function () {
    $instructor = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $this->actingAs($instructor);

    $create = $this->post(route('dashboard.courses.store'), [
        'title' => 'My Course',
        'slug' => 'my-course',
        'description' => 'Desc',
        'language' => 'en',
        'is_free' => true,
    ]);
    $create->assertRedirect();

    $course = Course::where('slug', 'my-course')->first();
    expect($course)->not->toBeNull();
    expect($course->instructor_id)->toBe($instructor->id);
    expect($course->status)->toBe(Course::STATUS_DRAFT);

    $update = $this->put(route('dashboard.courses.update', $course), [
        'title' => 'My Course Updated',
        'slug' => 'my-course',
        'description' => 'New Desc',
        'language' => 'en',
        'is_free' => true,
    ]);
    $update->assertRedirect();

    $course->refresh();
    expect($course->title)->toBe('My Course Updated');

    $delete = $this->delete(route('dashboard.courses.destroy', $course));
    $delete->assertRedirect();
    expect(Course::where('slug', 'my-course')->exists())->toBeFalse();
});

it('instructor can create edit delete lessons and set position', function () {
    $instructor = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $this->actingAs($instructor);
    $course = Course::create([
        'title' => 'C',
        'slug' => 'c',
        'status' => Course::STATUS_DRAFT,
        'language' => 'en',
        'instructor_id' => $instructor->id,
    ]);

    $create = $this->post(route('dashboard.courses.lessons.store', $course), [
        'title' => 'L1',
        'slug' => 'l1',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'position' => 3,
    ]);
    $create->assertRedirect();

    $lesson = Lesson::where('course_id', $course->id)->where('slug', 'l1')->first();
    expect($lesson)->not->toBeNull();
    expect($lesson->position)->toBe(3);

    $update = $this->put(route('dashboard.lessons.update', $lesson), [
        'title' => 'L1x',
        'slug' => 'l1',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'position' => 5,
    ]);
    $update->assertRedirect();
    $lesson->refresh();
    expect($lesson->title)->toBe('L1x');
    expect($lesson->position)->toBe(5);

    $delete = $this->delete(route('dashboard.lessons.destroy', $lesson));
    $delete->assertRedirect();
    expect(Lesson::where('course_id', $course->id)->exists())->toBeFalse();
});

