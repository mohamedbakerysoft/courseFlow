<?php

namespace App\Actions\Payments;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\StripeService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\DB;

class CreateStripeCheckoutSessionAction
{
    public function __construct(private StripeService $stripe, private SettingsService $settings) {}

    public function execute(User $user, Course $course): array
    {
        if ($course->is_free || (float) $course->price <= 0.0) {
            throw new \RuntimeException('Course is free; no checkout required.');
        }

        return DB::transaction(function () use ($user, $course) {
            if (! (app()->environment('testing') || app()->environment('dusk') || app()->environment('dusk.local'))) {
                $enabled = (bool) $this->settings->get('payments.stripe.enabled', true);
                $secret = (string) $this->settings->get('stripe.secret_key', '');
                $publishable = (string) $this->settings->get('stripe.publishable_key', '');
                if (! $enabled || $secret === '' || $publishable === '') {
                    throw new \RuntimeException('Stripe is not enabled or keys are missing.');
                }
            }
            $successUrl = route('payments.success');
            $cancelUrl = route('payments.cancel', ['course' => $course->slug]);
            $session = $this->stripe->createCheckoutSession($user, $course, $successUrl, $cancelUrl);

            $existing = Payment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', Payment::STATUS_PENDING)
                ->first();

            if ($existing) {
                $existing->provider = 'stripe';
                $existing->amount = (float) $course->price;
                $existing->currency = $course->currency ?? 'USD';
                $existing->stripe_session_id = $session['id'];
                // If switching from another provider, clear unrelated identifiers
                $existing->external_reference = null;
                $existing->save();
            } else {
                Payment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'provider' => 'stripe',
                    'amount' => (float) $course->price,
                    'currency' => $course->currency ?? 'USD',
                    'status' => Payment::STATUS_PENDING,
                    'stripe_session_id' => $session['id'],
                ]);
            }

            return $session;
        });
    }
}
