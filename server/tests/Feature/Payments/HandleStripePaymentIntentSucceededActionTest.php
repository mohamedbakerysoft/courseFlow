<?php

use App\Actions\Payments\HandleStripePaymentIntentSucceededAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('marks payment paid and enrolls on intent succeeded', function () {
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Paid Intent',
        'slug' => 'paid-intent',
        'price' => 25,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    Payment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'provider' => 'stripe',
        'amount' => $course->price,
        'currency' => $course->currency,
        'status' => Payment::STATUS_PENDING,
        'stripe_session_id' => 'sess_demo',
    ]);

    $intent = [
        'id' => 'pi_demo_123',
        'metadata' => [
            'user_id' => (string) $user->id,
            'course_id' => (string) $course->id,
        ],
    ];

    app(HandleStripePaymentIntentSucceededAction::class)->execute($intent);

    $payment = Payment::where('user_id', $user->id)->where('course_id', $course->id)->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($payment->external_reference)->toBe('pi_demo_123');
    expect($user->courses()->where('course_id', $course->id)->exists())->toBeTrue();
});
