<?php

use App\Actions\Payments\CreateManualPaymentAction;
use App\Actions\Payments\CreatePayPalCheckoutAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('reuses existing pending paypal payment on repeated checkout', function () {
    app(\App\Services\SettingsService::class)->set(['paypal.webhook_secret' => 'whsec_test']);

    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Paid PayPal',
        'slug' => 'paid-paypal',
        'price' => 129,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    $firstOrder = app(CreatePayPalCheckoutAction::class)->execute($student, $course);
    $firstPayment = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('status', Payment::STATUS_PENDING)->first();
    expect($firstPayment)->not->toBeNull();
    expect($firstPayment->provider)->toBe('paypal');

    $secondOrder = app(CreatePayPalCheckoutAction::class)->execute($student, $course);
    $pending = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('status', Payment::STATUS_PENDING)->get();
    expect($pending)->toHaveCount(1);
    $payment = $pending->first();
    expect($payment->external_reference)->toBe($secondOrder['id']);
    expect($secondOrder['id'])->not->toBe($firstOrder['id']);
});

it('marks manual pending as failed when starting paypal checkout', function () {
    app(\App\Services\SettingsService::class)->set(['paypal.webhook_secret' => 'whsec_test']);

    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Manual Then PayPal',
        'slug' => 'manual-then-paypal',
        'price' => 99,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    app(CreateManualPaymentAction::class)->execute($student, $course);
    $paypalOrder = app(CreatePayPalCheckoutAction::class)->execute($student, $course);

    $manualFailed = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('provider', 'manual')->where('status', Payment::STATUS_FAILED)->exists();
    expect($manualFailed)->toBeTrue();
    $paypalPending = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('provider', 'paypal')->where('status', Payment::STATUS_PENDING)->first();
    expect($paypalPending)->not->toBeNull();
    expect($paypalPending->external_reference)->toBe($paypalOrder['id']);
});

