<?php

use App\Actions\Payments\CapturePayPalOrderAction;
use App\Actions\Payments\CreatePayPalCheckoutAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('captures order and enrolls user', function () {
    $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $course = Course::create([
        'title' => 'PP Cap',
        'slug' => 'pp-cap',
        'price' => 35,
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

    app(CapturePayPalOrderAction::class)->execute($orderId);

    $payment->refresh();
    expect($payment->status)->toBe(Payment::STATUS_PAID);
    expect($user->courses()->where('course_id', $course->id)->exists())->toBeTrue();
});
