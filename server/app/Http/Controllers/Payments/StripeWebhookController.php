<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\HandleStripePaymentSuccessAction;
use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function __construct(private StripeService $stripe, private HandleStripePaymentSuccessAction $successHandler)
    {
    }

    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature', '');

        try {
            $event = $this->stripe->constructEvent($payload, $sigHeader);
        } catch (\Throwable $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object->toArray();
            $this->successHandler->execute($session);
        }

        return response('ok', 200);
    }
}

