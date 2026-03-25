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
    $payment->update([
        'payment_reference' => 'BANK-REF-123',
        'proof_path' => 'manual-payments/proof.png',
    ]);
    expect($payment->status)->toBe(Payment::STATUS_PENDING);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeFalse();
    app(ApproveManualPaymentAction::class)->execute($payment, $instructor);
    $payment->refresh();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($payment->approved_by)->toBe($instructor->id);
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeTrue();
});

it('manual approval restores enrollment when another payment is already marked paid', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $course = Course::create([
        'title' => 'Recovered Access Course',
        'slug' => 'recovered-access-course',
        'price' => 60,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    Payment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'provider' => 'paypal',
        'amount' => 60,
        'currency' => 'USD',
        'status' => Payment::STATUS_PAID,
        'external_reference' => 'paypal_paid_existing',
    ]);

    $manualPayment = app(CreateManualPaymentAction::class)->execute($student, $course);
    $manualPayment->update([
        'payment_reference' => 'BANK-REF-RECOVER',
        'proof_path' => 'manual-payments/proof-recover.png',
        'submitted_at' => now(),
    ]);

    $this->actingAs($admin)
        ->post(route('dashboard.payments.approve', $manualPayment))
        ->assertRedirect(route('dashboard.finance.manual_payments'));

    $manualPayment->refresh();

    expect($manualPayment->status)->toBe(Payment::STATUS_FAILED);
    expect($manualPayment->review_notes)->toContain('Access was already granted');
    expect($student->courses()->where('course_id', $course->id)->exists())->toBeTrue();
});

it('manual approval replaces older failed duplicates when reconciling an existing paid payment', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $course = Course::create([
        'title' => 'Recovered Access Duplicate Cleanup',
        'slug' => 'recovered-access-duplicate-cleanup',
        'price' => 70,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    Payment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'provider' => 'paypal',
        'amount' => 70,
        'currency' => 'USD',
        'status' => Payment::STATUS_PAID,
        'external_reference' => 'paypal_paid_for_cleanup',
    ]);

    Payment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'provider' => 'paypal',
        'amount' => 70,
        'currency' => 'USD',
        'status' => Payment::STATUS_FAILED,
        'external_reference' => 'older_failed_duplicate',
    ]);

    $manualPayment = app(CreateManualPaymentAction::class)->execute($student, $course);
    $manualPayment->update([
        'payment_reference' => 'BANK-REF-CLEANUP',
        'proof_path' => 'manual-payments/proof-cleanup.png',
        'submitted_at' => now(),
    ]);

    $this->actingAs($admin)
        ->post(route('dashboard.payments.approve', $manualPayment))
        ->assertRedirect(route('dashboard.finance.manual_payments'));

    $payments = Payment::query()
        ->where('user_id', $student->id)
        ->where('course_id', $course->id)
        ->orderBy('id')
        ->get();

    expect($payments)->toHaveCount(2);
    expect($payments->where('status', Payment::STATUS_PAID))->toHaveCount(1);
    expect($payments->where('status', Payment::STATUS_FAILED))->toHaveCount(1);
    expect($payments->last()->external_reference)->toBe($manualPayment->external_reference);
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
    $manual->update([
        'payment_reference' => 'BANK-REF-456',
        'proof_path' => 'manual-payments/proof-2.png',
    ]);
    app(ApproveManualPaymentAction::class)->execute($manual, $instructor);
    $paidCount = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('status', Payment::STATUS_PAID)->count();
    expect($paidCount)->toBe(1);
});
