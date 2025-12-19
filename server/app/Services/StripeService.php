<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeService
{
    protected ?StripeClient $client = null;

    public function __construct()
    {
        $secret = config('services.stripe.secret');
        if ($secret && !app()->environment('testing') && !app()->environment('dusk') && !app()->environment('dusk.local')) {
            $this->client = new StripeClient($secret);
        }
    }

    public function createCheckoutSession(User $user, Course $course, string $successUrl, string $cancelUrl): array
    {
        $amountCents = (int) round(((float) $course->price) * 100);
        $currency = $course->currency ?: 'USD';

        $metadata = [
            'user_id' => (string) $user->id,
            'course_id' => (string) $course->id,
        ];

        if ($this->client) {
            $session = $this->client->checkout->sessions->create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => ['name' => $course->title],
                        'unit_amount' => $amountCents,
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'client_reference_id' => (string) $user->id,
                'metadata' => $metadata,
            ]);
            return ['id' => $session->id, 'url' => $session->url];
        }

        // Fake mode for testing/dusk: return relative path to avoid host/port mismatch
        $fakeId = 'sess_' . bin2hex(random_bytes(8));
        $fakeUrl = route('payments.success', ['session_id' => $fakeId], false);
        return ['id' => $fakeId, 'url' => $fakeUrl, 'metadata' => $metadata];
    }

    public function constructEvent(string $payload, string $signatureHeader)
    {
        $webhookSecret = config('services.stripe.webhook_secret');
        if (app()->environment('testing') || app()->environment('dusk') || app()->environment('dusk.local')) {
            // Manual verification for tests/dusk
            // Expected header: "t=timestamp,v1=signature"
            $parts = [];
            foreach (explode(',', $signatureHeader) as $pair) {
                [$k, $v] = array_pad(explode('=', trim($pair), 2), 2, null);
                if ($k && $v) {
                    $parts[$k] = $v;
                }
            }
            $ts = $parts['t'] ?? null;
            $sig = $parts['v1'] ?? null;
            if (! $ts || ! $sig) {
                throw new \RuntimeException('Invalid signature header');
            }
            $expected = hash_hmac('sha256', $ts . '.' . $payload, (string) $webhookSecret);
            if (! hash_equals($expected, $sig)) {
                throw new \RuntimeException('Signature mismatch');
            }
            $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
            return \Stripe\Event::constructFrom($data);
        }
        return Webhook::constructEvent($payload, $signatureHeader, $webhookSecret);
    }
}
