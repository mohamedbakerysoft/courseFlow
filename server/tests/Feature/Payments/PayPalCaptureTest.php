<?php

use App\Actions\Payments\CapturePayPalOrderAction;
use App\Actions\Payments\CreatePayPalCheckoutAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;

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

it('sends a valid empty json payload when capturing a paypal order', function () {
    app(\App\Services\SettingsService::class)->set([
        'paypal.client_id' => 'paypal-client-id',
        'paypal.client_secret' => 'paypal-secret',
        'paypal.mode' => 'sandbox',
    ]);

    $service = new class(app(\App\Services\SettingsService::class)) extends \App\Services\PayPalService
    {
        protected function shouldMockGateway(): bool
        {
            return false;
        }
    };

    Http::fake([
        'https://api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
            'access_token' => 'sandbox-token',
        ], 200),
        'https://api-m.sandbox.paypal.com/v2/checkout/orders/order_live_capture/capture' => function (HttpRequest $request) {
            expect($request->header('Content-Type'))->toContain('application/json');
            expect($request->body())->toBe('{}');

            return Http::response([
                'id' => 'capture_123',
                'status' => 'COMPLETED',
            ], 201);
        },
    ]);

    $result = $service->captureOrder('order_live_capture');

    expect($result['status'])->toBe('COMPLETED');
    expect($result['http_status'])->toBe(201);
});
