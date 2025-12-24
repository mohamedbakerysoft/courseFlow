<?php

namespace App\Actions\Payments;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use App\Services\PayPalService;
use Illuminate\Support\Facades\DB;

class CapturePayPalOrderAction
{
    public function __construct(private PayPalService $paypal, private EnrollUserInCourseAction $enroller) {}

    public function execute(string $orderId): void
    {
        DB::transaction(function () use ($orderId) {
            $payment = Payment::where('external_reference', $orderId)
                ->where('provider', 'paypal')
                ->first();
            if (! $payment) {
                return;
            }

            $alreadyPaid = Payment::where('user_id', $payment->user_id)
                ->where('course_id', $payment->course_id)
                ->where('status', Payment::STATUS_PAID)
                ->exists();
            if ($alreadyPaid) {
                return;
            }

            $result = $this->paypal->captureOrder($orderId);
            if (($result['status'] ?? '') !== 'COMPLETED') {
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
