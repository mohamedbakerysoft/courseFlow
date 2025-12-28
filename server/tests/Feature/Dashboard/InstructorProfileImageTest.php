<?php

use App\Models\Course;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('uploads instructor image and stores setting', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    \Pest\Laravel\actingAs($admin)
        ->post(route('dashboard.instructor_profile.update'), [
            'instructor_image' => UploadedFile::fake()->image('face.jpg', 300, 300),
        ])
        ->assertRedirect();

    $stored = Setting::where('key', 'landing.instructor_image')->first();
    expect($stored)->not()->toBeNull();
    expect(Storage::disk('public')->exists($stored->value))->toBeTrue();
});

it('shows uploaded instructor image on course page', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    \Pest\Laravel\actingAs($admin)
        ->post(route('dashboard.instructor_profile.update'), [
            'instructor_image' => UploadedFile::fake()->image('face2.jpg', 256, 256),
        ])
        ->assertRedirect();

    $path = Setting::where('key', 'landing.instructor_image')->value('value');
    $course = Course::create([
        'title' => 'Face Course',
        'slug' => 'face-course',
        'description' => 'Desc',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'language' => 'en',
        'status' => Course::STATUS_PUBLISHED,
        'instructor_id' => $admin->id,
    ]);

    $response = \Pest\Laravel\get(route('courses.show', $course));
    $response->assertOk();
    $response->assertSee('/storage/'.$path, false);
});
