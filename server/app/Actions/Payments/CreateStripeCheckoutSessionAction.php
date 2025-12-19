<?php

namespace App\Actions\Payments;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Support\Facades\DB;

class CreateStripeCheckoutSessionAction
{
    public function __construct(private StripeService $stripe) {}

    public function execute(User $user, Course $course): array
    {
        if ($course->is_free || (float) $course->price <= 0.0) {
            throw new \RuntimeException('Course is free; no checkout required.');
        }

        return DB::transaction(function () use ($user, $course) {
            $successUrl = route('payments.success');
            $cancelUrl = route('payments.cancel', ['course' => $course->slug]);
            $session = $this->stripe->createCheckoutSession($user, $course, $successUrl, $cancelUrl);

            Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'provider' => 'stripe',
                'amount' => (float) $course->price,
                'currency' => $course->currency ?? 'USD',
                'status' => Payment::STATUS_PENDING,
                'stripe_session_id' => $session['id'],
            ]);

            return $session;
        });
    }
}

