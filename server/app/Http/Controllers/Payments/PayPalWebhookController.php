<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\CapturePayPalOrderAction;
use App\Http\Controllers\Controller;
use App\Support\PayPalWebhookPayload;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayPalWebhookController extends Controller
{
    public function __construct(private CapturePayPalOrderAction $captureAction, private \App\Services\SettingsService $settings) {}

    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('PayPal-Signature', '');
        $secret = (string) $this->settings->get('paypal.webhook_secret', '');

        try {
            $parts = [];
            foreach (explode(',', (string) $sigHeader) as $pair) {
                [$k, $v] = array_pad(explode('=', trim($pair), 2), 2, null);
                if ($k && $v) {
                    $parts[$k] = $v;
                }
            }
            $ts = $parts['t'] ?? null;
            $sig = $parts['v1'] ?? null;
            if (! $ts || ! $sig) {
                return response('Invalid signature', 400);
            }
            $expected = hash_hmac('sha256', $ts.'.'.$payload, $secret);
            if (! hash_equals($expected, $sig)) {
                return response('Invalid signature', 400);
            }
        } catch (\Throwable $e) {
            return response('Invalid signature', 400);
        }

        $webhookPayload = PayPalWebhookPayload::fromArray(json_decode($payload, true) ?? []);

        if ($webhookPayload->shouldCapture()) {
            $this->captureAction->execute($webhookPayload->orderId);
        }

        return response('ok', 200);
    }
}
