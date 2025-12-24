<?php

namespace App\Actions\Payments;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\PayPalService;
use Illuminate\Support\Facades\DB;

class CreatePayPalCheckoutAction
{
    public function __construct(private PayPalService $paypal) {}

    public function execute(User $user, Course $course): array
    {
        if ($course->is_free || (float) $course->price <= 0.0) {
            throw new \RuntimeException('Course is free; no checkout required.');
        }

        return DB::transaction(function () use ($user, $course) {
            $successUrl = route('payments.paypal.success');
            $cancelUrl = route('payments.paypal.cancel', ['course' => $course->slug]);
            $order = $this->paypal->createOrder($user, $course, $successUrl, $cancelUrl);

            $existingPending = Payment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', Payment::STATUS_PENDING)
                ->first();
            if ($existingPending) {
                if ($existingPending->provider === 'paypal') {
                    $existingPending->external_reference = $order['id'];
                    $existingPending->save();
                } else {
                    $existingPending->status = Payment::STATUS_FAILED;
                    $existingPending->save();
                    Payment::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'provider' => 'paypal',
                        'amount' => (float) $course->price,
                        'currency' => $course->currency ?? 'USD',
                        'status' => Payment::STATUS_PENDING,
                        'external_reference' => $order['id'],
                    ]);
                }
            } else {
                Payment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'provider' => 'paypal',
                    'amount' => (float) $course->price,
                    'currency' => $course->currency ?? 'USD',
                    'status' => Payment::STATUS_PENDING,
                    'external_reference' => $order['id'],
                ]);
            }

            return $order;
        });
    }
}
