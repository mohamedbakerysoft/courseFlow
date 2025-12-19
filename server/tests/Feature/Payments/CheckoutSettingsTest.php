<?php

use App\Models\Course;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createPaidCourse(): Course {
    return Course::create([
        'title' => 'Paid Course',
        'slug' => 'paid-course',
        'price' => 99,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
}

it('hides stripe checkout button when disabled', function () {
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaidCourse();

    app(SettingsService::class)->set([
        'payments.stripe.enabled' => false,
        'payments.paypal.enabled' => true,
        'payments.manual.instructions' => 'Bank transfer details',
    ]);

    $response = \Pest\Laravel\actingAs($user)->get(route('courses.show', $course));

    $response->assertOk();
    $response->assertDontSee('Buy Course');
    $response->assertSee('Pay with PayPal');
    $response->assertSee('Manual Payment');
});

it('hides paypal checkout button when disabled', function () {
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaidCourse();

    app(SettingsService::class)->set([
        'payments.stripe.enabled' => true,
        'payments.paypal.enabled' => false,
        'payments.manual.instructions' => 'Bank transfer details',
    ]);

    $response = \Pest\Laravel\actingAs($user)->get(route('courses.show', $course));

    $response->assertOk();
    $response->assertSee('Buy Course');
    $response->assertDontSee('Pay with PayPal');
    $response->assertSee('Manual Payment');
});

it('shows manual payment instructions on pending page when configured', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaidCourse();

    app(SettingsService::class)->set([
        'payments.stripe.enabled' => false,
        'payments.paypal.enabled' => false,
        'payments.manual.instructions' => 'Send the fee via bank transfer to XYZ.',
    ]);

    $payment = app(\App\Actions\Payments\CreateManualPaymentAction::class)->execute($student, $course);

    $response = \Pest\Laravel\actingAs($student)->get(route('payments.manual.pending', $payment));

    $response->assertOk();
    $response->assertSee('Send the fee via bank transfer to XYZ.');
});

it('shows disabled message when all payment methods are disabled', function () {
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaidCourse();

    app(SettingsService::class)->set([
        'payments.stripe.enabled' => false,
        'payments.paypal.enabled' => false,
        'payments.manual.instructions' => '',
    ]);

    $response = \Pest\Laravel\actingAs($user)->get(route('courses.show', $course));

    $response->assertOk();
    $response->assertSee('Payments are currently disabled. Please contact the instructor.');
    $response->assertDontSee('Buy Course');
    $response->assertDontSee('Pay with PayPal');
    $response->assertDontSee('Manual Payment');
});
