<?php

use App\Actions\Payments\ApproveManualPaymentAction;
use App\Actions\Payments\CreateManualPaymentAction;
use App\Actions\Payments\CreatePayPalCheckoutAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;

it('paypal payment success enrolls user', function () {
    app(\App\Services\SettingsService::class)->set(['paypal.webhook_secret' => 'whsec_test']);
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Paid PP',
        'slug' => 'paid-pp',
        'price' => 30,
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
    $ts = (string) time();
    $sig = hash_hmac('sha256', $ts.'.'.$orderId, (string) app(\App\Services\SettingsService::class)->get('paypal.webhook_secret', ''));
    $resp = $this->actingAs($user)->get(route('payments.paypal.success', ['order_id' => $orderId, 't' => $ts, 'sig' => $sig]));
    $resp->assertRedirect(route('courses.show', $course));
    $payment->refresh();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($user->courses()->where('course_id', $course->id)->exists())->toBeTrue();
});

it('paypal cancel does not enroll', function () {
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Paid PP2',
        'slug' => 'paid-pp2',
        'price' => 45,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
    app(CreatePayPalCheckoutAction::class)->execute($user, $course);
    $resp = $this->actingAs($user)->get(route('payments.paypal.cancel', $course));
    $resp->assertRedirect(route('courses.show', $course));
    $pending = Payment::where('user_id', $user->id)->where('course_id', $course->id)->where('provider', 'paypal')->latest()->first();
    expect($pending)->not->toBeNull();
    expect($pending->status)->toBe(Payment::STATUS_FAILED);
    expect($user->courses()->where('course_id', $course->id)->exists())->toBeFalse();
});

it('manual payment stays pending and approves enroll', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $instructor = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $course = Course::create([
        'title' => 'Paid Manual',
        'slug' => 'paid-manual',
        'price' => 60,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
    $payment = app(CreateManualPaymentAction::class)->execute($student, $course);
    expect($payment->status)->toBe(Payment::STATUS_PENDING);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeFalse();
    app(ApproveManualPaymentAction::class)->execute($payment, $instructor);
    $payment->refresh();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($payment->approved_by)->toBe($instructor->id);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeTrue();
});

it('duplicate payments prevented for paypal and manual', function () {
    app(\App\Services\SettingsService::class)->set(['paypal.webhook_secret' => 'whsec_test']);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $instructor = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $course = Course::create([
        'title' => 'Paid Dup',
        'slug' => 'paid-dup',
        'price' => 70,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);
    $order = app(CreatePayPalCheckoutAction::class)->execute($student, $course);
    $orderId = $order['id'];
    $ts = (string) time();
    $sig = hash_hmac('sha256', $ts.'.'.$orderId, (string) app(\App\Services\SettingsService::class)->get('paypal.webhook_secret', ''));
    $this->actingAs($student)->get(route('payments.paypal.success', ['order_id' => $orderId, 't' => $ts, 'sig' => $sig]))->assertRedirect();
    // Approving manual after paid should not create another paid record
    $manual = app(CreateManualPaymentAction::class)->execute($student, $course);
    app(ApproveManualPaymentAction::class)->execute($manual, $instructor);
    $paidCount = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('status', Payment::STATUS_PAID)->count();
    expect($paidCount)->toBe(1);
});
