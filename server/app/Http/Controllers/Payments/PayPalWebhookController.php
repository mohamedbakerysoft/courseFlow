<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\FinalizeVerifiedPayPalPaymentAction;
use App\Http\Controllers\Controller;
use App\Services\PayPalService;
use App\Support\PayPalWebhookPayload;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayPalWebhookController extends Controller
{
    public function __construct(
        private FinalizeVerifiedPayPalPaymentAction $finalizeAction,
        private PayPalService $paypal
    ) {}

    public function handle(Request $request): Response
    {
        $payload = $request->getContent();

        try {
            if (! $this->paypal->verifyWebhookSignature($payload, $request->headers->all())) {
                return response('Invalid signature', 400);
            }
        } catch (\Throwable $e) {
            return response('Invalid signature', 400);
        }

        $webhookPayload = PayPalWebhookPayload::fromArray(json_decode($payload, true) ?? []);

        if ($webhookPayload->shouldCapture()) {
            $this->finalizeAction->execute($webhookPayload->orderId);
        }

        return response('ok', 200);
    }
}
