<?php

use App\Actions\Payments\CreateManualPaymentAction;
use App\Actions\Payments\CreatePayPalCheckoutAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('reuses existing pending manual payment on repeated requests', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Manual Reuse',
        'slug' => 'manual-reuse',
        'price' => 39,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    $first = app(CreateManualPaymentAction::class)->execute($student, $course);
    $second = app(CreateManualPaymentAction::class)->execute($student, $course);

    expect($first->id)->toBe($second->id);
    $pendingCount = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('status', Payment::STATUS_PENDING)->count();
    expect($pendingCount)->toBe(1);
});

it('marks other provider pending as failed when starting manual', function () {
    app(\App\Services\SettingsService::class)->set(['paypal.webhook_secret' => 'whsec_test']);
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'Manual After PayPal',
        'slug' => 'manual-after-paypal',
        'price' => 39,
        'currency' => 'USD',
        'is_free' => false,
        'status' => Course::STATUS_PUBLISHED,
        'language' => 'en',
    ]);

    app(CreatePayPalCheckoutAction::class)->execute($student, $course);
    $manual = app(CreateManualPaymentAction::class)->execute($student, $course);

    expect($manual->provider)->toBe('manual');
    expect($manual->status)->toBe(Payment::STATUS_PENDING);
    $paypalPending = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('provider', 'paypal')->where('status', Payment::STATUS_PENDING)->exists();
    expect($paypalPending)->toBeFalse();
    $paypalFailed = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('provider', 'paypal')->where('status', Payment::STATUS_FAILED)->exists();
    expect($paypalFailed)->toBeTrue();
});
