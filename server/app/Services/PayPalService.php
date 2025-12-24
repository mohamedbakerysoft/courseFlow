<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PayPalService
{
    public function createOrder(User $user, Course $course, string $successUrl, string $cancelUrl): array
    {
        if (
            app()->runningUnitTests()
            || app()->environment(['local', 'testing', 'dusk', 'dusk.local'])
            || config('demo.enabled')
        ) {
            $orderId = 'order_'.Str::random(12);
            $secret = config('services.paypal.webhook_secret');
            $ts = (string) time();
            $payload = $orderId;
            $sig = hash_hmac('sha256', $ts.'.'.$payload, (string) $secret);
            $approveUrl = $successUrl.'?order_id='.$orderId.'&t='.$ts.'&sig='.$sig;

            return ['id' => $orderId, 'approve_url' => $approveUrl];
        }

        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');
        $baseUrl = rtrim(config('services.paypal.base_url'), '/');
        if (! $clientId || ! $clientSecret) {
            throw new \RuntimeException('PayPal credentials not configured');
        }

        $tokenResp = Http::asForm()->withBasicAuth($clientId, $clientSecret)
            ->post($baseUrl.'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);
        $accessToken = (string) ($tokenResp->json('access_token') ?? '');

        $amountValue = number_format((float) $course->price, 2, '.', '');
        $createResp = Http::withToken($accessToken)->post($baseUrl.'/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $course->currency ?? 'USD',
                    'value' => $amountValue,
                ],
                'custom_id' => (string) $user->id.':'.(string) $course->id,
            ]],
            'application_context' => [
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ],
        ]);

        $orderId = (string) ($createResp->json('id') ?? '');
        $links = $createResp->json('links') ?? [];
        $approveLink = null;
        foreach ($links as $link) {
            if (($link['rel'] ?? '') === 'approve') {
                $approveLink = $link['href'] ?? null;
                break;
            }
        }
        $approveUrl = $approveLink ?: $successUrl;

        return ['id' => $orderId, 'approve_url' => $approveUrl];
    }

    public function captureOrder(string $orderId): array
    {
        if (
            app()->runningUnitTests()
            || app()->environment(['local', 'testing', 'dusk', 'dusk.local'])
            || config('demo.enabled')
        ) {
            return ['id' => $orderId, 'status' => 'COMPLETED'];
        }

        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');
        $baseUrl = rtrim(config('services.paypal.base_url'), '/');
        $tokenResp = Http::asForm()->withBasicAuth($clientId, $clientSecret)
            ->post($baseUrl.'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);
        $accessToken = (string) ($tokenResp->json('access_token') ?? '');

        $resp = Http::withToken($accessToken)->post($baseUrl.'/v2/checkout/orders/'.$orderId.'/capture', []);
        $status = (string) ($resp->json('status') ?? '');

        return ['id' => $orderId, 'status' => $status];
    }

    public function verifyOrder(string $orderId, ?string $ts = null, ?string $sig = null): bool
    {
        if (
            app()->runningUnitTests()
            || app()->environment(['local', 'testing', 'dusk', 'dusk.local'])
            || config('demo.enabled')
        ) {
            $secret = config('services.paypal.webhook_secret');
            if (! $ts || ! $sig) {
                return false;
            }
            $expected = hash_hmac('sha256', $ts.'.'.$orderId, (string) $secret);

            return hash_equals($expected, $sig);
        }

        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');
        $baseUrl = rtrim(config('services.paypal.base_url'), '/');
        $tokenResp = Http::asForm()->withBasicAuth($clientId, $clientSecret)
            ->post($baseUrl.'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);
        $accessToken = (string) ($tokenResp->json('access_token') ?? '');

        $resp = Http::withToken($accessToken)->get($baseUrl.'/v2/checkout/orders/'.$orderId);
        $status = (string) ($resp->json('status') ?? '');

        return $status === 'COMPLETED';
    }
}
