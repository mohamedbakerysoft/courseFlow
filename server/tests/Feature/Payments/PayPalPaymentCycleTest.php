<?php

use App\Actions\Dashboard\Finance\GetFinanceStatsAction;
use App\Actions\Payments\CreatePayPalCheckoutAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\PayPalService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createPaypalPaidCourse(): Course
{
    return Course::create([
        'title' => 'PayPal Paid Course',
        'slug' => 'paypal-paid-course',
        'price' => 89,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
}

function paypalWebhookSignature(string $payload, string $secret): string
{
    $ts = (string) time();
    $sig = hash_hmac('sha256', $ts.'.'.$payload, $secret);

    return "t={$ts},v1={$sig}";
}

it('does not enroll the student when a paypal order is only created', function () {
    app(\App\Services\SettingsService::class)->set(['paypal.webhook_secret' => 'whsec_test']);

    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaypalPaidCourse();

    $order = app(CreatePayPalCheckoutAction::class)->execute($student, $course);

    expect($order['id'])->not->toBe('');
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeFalse();

    $payment = Payment::query()
        ->where('user_id', $student->id)
        ->where('course_id', $course->id)
        ->where('provider', 'paypal')
        ->latest()
        ->first();

    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::STATUS_PENDING);
});

it('does not grant access when paypal capture is not completed', function () {
    app()->instance(PayPalService::class, new class(app(\App\Services\SettingsService::class)) extends PayPalService
    {
        public function createOrder(User $user, Course $course, string $successUrl, string $cancelUrl): array
        {
            return ['id' => 'order_incomplete', 'approve_url' => $successUrl];
        }

        public function captureOrder(string $orderId): array
        {
            return ['id' => $orderId, 'status' => 'PAYER_ACTION_REQUIRED'];
        }
    });

    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaypalPaidCourse();

    app(CreatePayPalCheckoutAction::class)->execute($student, $course);

    $this->actingAs($student)
        ->postJson(route('payments.paypal.capture'), ['order_id' => 'order_incomplete'])
        ->assertStatus(422)
        ->assertJson([
            'ok' => false,
            'reason' => 'capture_incomplete',
            'status' => 'PAYER_ACTION_REQUIRED',
        ]);

    $payment = Payment::query()->where('external_reference', 'order_incomplete')->first();

    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::STATUS_PENDING);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeFalse();
});

it('grants access once after a completed paypal capture', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaypalPaidCourse();

    $order = app(CreatePayPalCheckoutAction::class)->execute($student, $course);

    $this->actingAs($student)
        ->postJson(route('payments.paypal.capture'), ['order_id' => $order['id']])
        ->assertOk()
        ->assertJson([
            'ok' => true,
            'status' => 'COMPLETED',
        ]);

    $payment = Payment::query()->where('external_reference', $order['id'])->first();

    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeTrue();

    $this->actingAs($student)
        ->postJson(route('payments.paypal.capture'), ['order_id' => $order['id']])
        ->assertOk();

    $paidCount = Payment::query()
        ->where('user_id', $student->id)
        ->where('course_id', $course->id)
        ->where('provider', 'paypal')
        ->where('status', Payment::STATUS_PAID)
        ->count();

    expect($paidCount)->toBe(1);

    $financeStats = app(GetFinanceStatsAction::class)->execute();

    $topCourse = $financeStats['sales_per_course']->items()[0] ?? null;

    expect($financeStats['all_time_sales'])->toBe(89.0);
    expect($topCourse)->not->toBeNull();
    expect((int) $topCourse->cnt)->toBe(1);
});

it('marks paypal payment as failed when success verification is invalid', function () {
    app(\App\Services\SettingsService::class)->set(['paypal.webhook_secret' => 'whsec_test']);

    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaypalPaidCourse();
    $order = app(CreatePayPalCheckoutAction::class)->execute($student, $course);

    $this->actingAs($student)
        ->get(route('payments.paypal.success', ['order_id' => $order['id'], 't' => time(), 'sig' => 'invalid']))
        ->assertRedirect(route('courses.show', $course));

    $payment = Payment::query()->where('external_reference', $order['id'])->first();

    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::STATUS_FAILED);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeFalse();
});

it('processes only completed paypal capture webhooks and uses the related order id', function () {
    app(\App\Services\SettingsService::class)->set(['paypal.webhook_secret' => 'whsec_test']);

    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = createPaypalPaidCourse();
    $order = app(CreatePayPalCheckoutAction::class)->execute($student, $course);

    $ignoredPayload = json_encode([
        'event_type' => 'CHECKOUT.ORDER.APPROVED',
        'resource' => [
            'id' => 'some-other-id',
            'supplementary_data' => [
                'related_ids' => ['order_id' => $order['id']],
            ],
        ],
    ], JSON_THROW_ON_ERROR);

    $this->call('POST', route('payments.webhook.paypal'), [], [], [], [
        'HTTP_PayPal-Signature' => paypalWebhookSignature($ignoredPayload, 'whsec_test'),
        'CONTENT_TYPE' => 'application/json',
    ], $ignoredPayload)->assertOk();

    $payment = Payment::query()->where('external_reference', $order['id'])->first();
    expect($payment)->not->toBeNull();
    expect($payment->status)->toBe(Payment::STATUS_PENDING);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeFalse();

    $completedPayload = json_encode([
        'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
        'resource' => [
            'id' => 'capture_123',
            'supplementary_data' => [
                'related_ids' => ['order_id' => $order['id']],
            ],
        ],
    ], JSON_THROW_ON_ERROR);

    $this->call('POST', route('payments.webhook.paypal'), [], [], [], [
        'HTTP_PayPal-Signature' => paypalWebhookSignature($completedPayload, 'whsec_test'),
        'CONTENT_TYPE' => 'application/json',
    ], $completedPayload)->assertOk();

    $payment->refresh();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeTrue();
});
