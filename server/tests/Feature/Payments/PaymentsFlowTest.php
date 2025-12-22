<?php

use App\Actions\Payments\CreateStripeCheckoutSessionAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function stripeSignature(string $payload, string $secret): string
{
    $ts = (string) time();
    $sig = hash_hmac('sha256', $ts.'.'.$payload, $secret);

    return "t={$ts},v1={$sig}";
}

it('paid course blocked without payment', function () {
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Paid',
        'slug' => 'paid',
        'price' => 100,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    $this->actingAs($user)
        ->post(route('courses.enroll', $course))
        ->assertForbidden();
});

it('free course enrolls directly', function () {
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Free',
        'slug' => 'free',
        'price' => 0,
        'currency' => 'USD',
        'is_free' => true,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
    $this->actingAs($user)
        ->post(route('courses.enroll', $course))
        ->assertRedirect(route('courses.show', $course));
    expect($user->courses()->where('course_id', $course->id)->exists())->toBeTrue();
});

it('payment success enrolls user and prevents duplicate', function () {
    config()->set('services.stripe.webhook_secret', 'whsec_test');

    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Paid 2',
        'slug' => 'paid-2',
        'price' => 50,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    $session = app(CreateStripeCheckoutSessionAction::class)->execute($user, $course);
    $payment = Payment::where('stripe_session_id', $session['id'])->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::STATUS_PENDING);

    $payload = json_encode([
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => $session['id'],
                'metadata' => [
                    'user_id' => (string) $user->id,
                    'course_id' => (string) $course->id,
                ],
            ],
        ],
    ], JSON_THROW_ON_ERROR);
    $sig = stripeSignature($payload, 'whsec_test');

    $this->call(
        'POST',
        route('payments.webhook.stripe'),
        [], // parameters
        [], // cookies
        [], // files
        ['HTTP_Stripe-Signature' => $sig, 'CONTENT_TYPE' => 'application/json'],
        $payload // raw content
    )->assertOk();

    $payment->refresh();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($user->courses()->where('course_id', $course->id)->exists())->toBeTrue();

    // Duplicate webhook should not create duplicate paid records
    $this->call(
        'POST',
        route('payments.webhook.stripe'),
        [],
        [],
        [],
        ['HTTP_Stripe-Signature' => $sig, 'CONTENT_TYPE' => 'application/json'],
        $payload
    )->assertOk();
    $paidCount = Payment::where('user_id', $user->id)->where('course_id', $course->id)->where('status', Payment::STATUS_PAID)->count();
    expect($paidCount)->toBe(1);
});

it('webhook signature verified', function () {
    config()->set('services.stripe.webhook_secret', 'whsec_test');

    $payload = json_encode(['type' => 'checkout.session.completed', 'data' => ['object' => ['id' => 'sess_x', 'metadata' => []]]], JSON_THROW_ON_ERROR);
    $this->call(
        'POST',
        route('payments.webhook.stripe'),
        [],
        [],
        [],
        ['HTTP_Stripe-Signature' => 't=0,v1=invalid', 'CONTENT_TYPE' => 'application/json'],
        $payload
    )->assertStatus(400);
});
