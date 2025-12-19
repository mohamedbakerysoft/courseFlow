<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads instructor profile page without auth', function () {
    User::factory()->create([
        'name' => 'Admin One',
        'role' => User::ROLE_ADMIN,
        'bio' => 'Instructor bio',
        'social_links' => ['twitter' => 'https://twitter.com/example'],
    ]);

    Course::create([
        'title' => 'Published Course',
        'thumbnail_path' => null,
        'status' => \App\Models\Course::STATUS_PUBLISHED,
    ]);

    $response = \Pest\Laravel\get('/instructor');
    $response->assertOk();
    $response->assertSee('Admin One');
    $response->assertSee('Published Course');
});

