<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('landing page loads successfully', function () {
    $response = \Pest\Laravel\get('/');

    $response->assertOk();
    $response->assertSee('Courses');
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

it('instructor settings override landing data', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    \Pest\Laravel\actingAs($admin)->post(route('dashboard.instructor_profile.update'), [
        'instructor_name' => 'Settings Instructor',
        'instructor_bio' => 'Settings bio text here',
        'hero_headline' => 'Instructor Headline',
        'hero_subheadline' => 'Instructor Subheadline',
    ])->assertRedirect();

    $response = \Pest\Laravel\get('/');
    $response->assertOk();
    $response->assertSee('Settings Instructor');
    $response->assertSee('Settings bio text here');
    $response->assertSee('Instructor Headline');
    $response->assertSee('Instructor Subheadline');
});

it('landing hero image mode defaults to contain', function () {
    $response = \Pest\Laravel\get('/');
    $response->assertOk();
    $response->assertSee('object-contain');
    $response->assertSee('transition-transform');
});

it('admin can switch hero image mode to cover', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    \Pest\Laravel\actingAs($admin)->post(route('dashboard.instructor_profile.update'), [
        'hero_image_mode' => 'cover',
    ])->assertRedirect();

    $response = \Pest\Laravel\get('/');
    $response->assertOk();
    $response->assertSee('object-cover');
    $response->assertSee('transition-transform');
});

it('admin can toggle landing sections visibility', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    \Pest\Laravel\actingAs($admin)->post(route('dashboard.settings.update'), [
        'default_language' => 'en',
        'payments_stripe_enabled' => false,
        'payments_paypal_enabled' => false,
        'payments_manual_instructions' => '',
        'landing_show_hero' => false,
        'landing_show_courses_preview' => false,
        'landing_show_testimonials' => false,
        'landing_show_footer_cta' => false,
    ])->assertRedirect();

    $response = \Pest\Laravel\get('/');
    $response->assertOk();
    $response->assertDontSee('Teach and sell your courses with CourseFlow');
    $response->assertDontSee('Featured courses');
    $response->assertDontSee('What people say after working together');
    $response->assertDontSee('Ready to take your next sales step?');
});

it('admin can hide instructor bio block inside hero', function () {
    $instructor = User::factory()->create([
        'name' => 'Landing Admin',
        'role' => User::ROLE_ADMIN,
        'bio' => 'Landing bio',
    ]);

    \Pest\Laravel\actingAs($instructor)->post(route('dashboard.settings.update'), [
        'default_language' => 'en',
        'landing_show_about' => false,
    ])->assertRedirect();

    $response = \Pest\Laravel\get('/');
    $response->assertOk();
    $response->assertSee('Landing Admin');
    $response->assertDontSee('Landing bio');
});
