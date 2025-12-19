<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows only published courses on listing', function () {
    Course::create([
        'title' => 'Published One',
        'slug' => 'published-one',
        'description' => 'Visible course',
        'price' => 10.00,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    Course::create([
        'title' => 'Draft One',
        'slug' => 'draft-one',
        'description' => 'Not visible',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_DRAFT,
        'language' => 'en',
    ]);

    $response = \Pest\Laravel\get('/courses');
    $response->assertOk();
    $response->assertSee('Published One');
    $response->assertDontSee('Draft One');
});

it('loads course detail by slug', function () {
    Course::create([
        'title' => 'Course A',
        'slug' => 'course-a',
        'description' => 'Full description',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    \Pest\Laravel\get('/courses/course-a')
        ->assertOk()
        ->assertSee('Course A');
});

it('returns 404 for invalid slug', function () {
    \Pest\Laravel\get('/courses/invalid-slug')->assertNotFound();
});

it('does not show draft course detail', function () {
    Course::create([
        'title' => 'Hidden Course',
        'slug' => 'hidden-course',
        'description' => 'Hidden',
        'price' => 25,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_DRAFT,
        'language' => 'en',
    ]);

    \Pest\Laravel\get('/courses/hidden-course')->assertNotFound();
});

