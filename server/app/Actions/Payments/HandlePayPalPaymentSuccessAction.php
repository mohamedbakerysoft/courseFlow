<?php

namespace App\Actions\Payments;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\PayPalService;
use App\Actions\Courses\EnrollUserInCourseAction;
use Illuminate\Support\Facades\DB;

class HandlePayPalPaymentSuccessAction
{
    public function __construct(private PayPalService $paypal, private EnrollUserInCourseAction $enroller) {}

    public function execute(string $orderId, ?string $ts, ?string $sig): void
    {
        $verified = $this->paypal->verifyOrder($orderId, $ts, $sig);
        DB::transaction(function () use ($verified, $orderId) {
            $payment = Payment::where('external_reference', $orderId)->where('provider', 'paypal')->first();
            if (! $payment) {
                return;
            }

            if (! $verified) {
                $payment->status = Payment::STATUS_FAILED;
                $payment->save();
                return;
            }

            $alreadyPaid = Payment::where('user_id', $payment->user_id)
                ->where('course_id', $payment->course_id)
                ->where('status', Payment::STATUS_PAID)
                ->exists();
            if ($alreadyPaid) {
                return;
            }

            $payment->status = Payment::STATUS_PAID;
            $payment->save();

            $user = User::find($payment->user_id);
            $course = Course::find($payment->course_id);
            if ($user && $course) {
                $this->enroller->execute($user, $course);
            }
        });
    }
}
