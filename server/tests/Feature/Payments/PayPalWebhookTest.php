<?php

use App\Actions\Payments\CreatePayPalCheckoutAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function paypalSignature(string $payload, string $secret): string
{
    $ts = (string) time();
    $sig = hash_hmac('sha256', $ts.'.'.$payload, $secret);

    return "t={$ts},v1={$sig}";
}

it('webhook signature verified for paypal', function () {
    config()->set('services.paypal.webhook_secret', 'whsec_test');
    $payload = json_encode(['type' => 'PAYMENT.CAPTURE.COMPLETED', 'data' => ['object' => ['id' => 'order_x']]], JSON_THROW_ON_ERROR);
    \Pest\Laravel\postJson(route('payments.webhook.paypal'), [], ['PayPal-Signature' => 't=0,v1=invalid'])
        ->assertStatus(400);
    $sig = paypalSignature($payload, 'whsec_test');
    \Pest\Laravel\call('POST', route('payments.webhook.paypal'), [], [], [], ['HTTP_PayPal-Signature' => $sig, 'CONTENT_TYPE' => 'application/json'], $payload)
        ->assertOk();
});

it('webhook enrolls user idempotently', function () {
    config()->set('services.paypal.webhook_secret', 'whsec_test');
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'PP Webhook',
        'slug' => 'pp-webhook',
        'price' => 42,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
    $order = app(CreatePayPalCheckoutAction::class)->execute($user, $course);
    $orderId = $order['id'];
    $payment = Payment::where('external_reference', $orderId)->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::STATUS_PENDING);

    $payload = json_encode(['type' => 'PAYMENT.CAPTURE.COMPLETED', 'data' => ['object' => ['id' => $orderId]]], JSON_THROW_ON_ERROR);
    $sig = paypalSignature($payload, 'whsec_test');
    \Pest\Laravel\call('POST', route('payments.webhook.paypal'), [], [], [], ['HTTP_PayPal-Signature' => $sig, 'CONTENT_TYPE' => 'application/json'], $payload)
        ->assertOk();

    $payment->refresh();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($user->courses()->where('course_id', $course->id)->exists())->toBeTrue();

    \Pest\Laravel\call('POST', route('payments.webhook.paypal'), [], [], [], ['HTTP_PayPal-Signature' => $sig, 'CONTENT_TYPE' => 'application/json'], $payload)
        ->assertOk();
    $paidCount = Payment::where('user_id', $user->id)->where('course_id', $course->id)->where('status', Payment::STATUS_PAID)->count();
    expect($paidCount)->toBe(1);
});
