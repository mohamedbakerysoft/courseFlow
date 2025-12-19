<?php

use App\Models\Course;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('landing page loads successfully', function () {
    $response = \Pest\Laravel\get('/');

    $response->assertOk();
    $response->assertSee('Browse Courses');
});

it('landing page shows featured courses', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    Course::create([
        'title' => 'Featured One',
        'slug' => 'featured-one',
        'description' => 'First featured course',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'language' => 'en',
        'status' => Course::STATUS_PUBLISHED,
        'instructor_id' => $admin->id,
    ]);

    $response = \Pest\Laravel\get('/');

    $response->assertOk();
    $response->assertSee('Featured courses');
    $response->assertSee('Featured One');
});

it('landing page uses instructor data', function () {
    $instructor = User::factory()->create([
        'name' => 'Landing Admin',
        'role' => User::ROLE_ADMIN,
        'bio' => 'Landing bio',
        'social_links' => ['twitter' => 'https://twitter.com/example'],
    ]);

    Course::create([
        'title' => 'Landing Course',
        'slug' => 'landing-course',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'language' => 'en',
        'status' => Course::STATUS_PUBLISHED,
        'instructor_id' => $instructor->id,
    ]);

    $response = \Pest\Laravel\get('/');

    $response->assertOk();
    $response->assertSee('Landing Admin');
    $response->assertSee('Landing bio');
});

it('landing settings are saved and reflected', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    \Pest\Laravel\actingAs($admin)->post(route('dashboard.settings.update'), [
        'default_language' => 'en',
        'payments_stripe_enabled' => false,
        'payments_paypal_enabled' => false,
        'payments_manual_instructions' => '',
        'landing_hero_title' => 'Custom Hero',
        'landing_hero_subtitle' => 'Custom Subtitle',
        'landing_feature_1_title' => 'F1',
        'landing_feature_1_description' => 'F1 desc',
        'landing_feature_2_title' => 'F2',
        'landing_feature_2_description' => 'F2 desc',
        'landing_feature_3_title' => 'F3',
        'landing_feature_3_description' => 'F3 desc',
    ])->assertRedirect();

    $response = \Pest\Laravel\get('/');

    $response->assertOk();
    $response->assertSee('Custom Hero');
    $response->assertSee('Custom Subtitle');
});

it('course cards appear on landing and courses index', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $course = Course::create([
        'title' => 'Card Course',
        'slug' => 'card-course',
        'description' => 'For card view',
        'price' => 10,
        'currency' => 'USD',
        'is_free' => false,
        'language' => 'en',
        'status' => Course::STATUS_PUBLISHED,
        'instructor_id' => $admin->id,
    ]);

    $landing = \Pest\Laravel\get('/');
    $landing->assertOk();
    $landing->assertSee('Card Course');

    $index = \Pest\Laravel\get(route('courses.index'));
    $index->assertOk();
    $index->assertSee('Card Course');
});

