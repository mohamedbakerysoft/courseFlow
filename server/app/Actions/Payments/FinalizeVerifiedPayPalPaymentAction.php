<?php

namespace App\Actions\Payments;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FinalizeVerifiedPayPalPaymentAction
{
    public function __construct(private EnrollUserInCourseAction $enroller) {}

    public function execute(string $orderId): array
    {
        return DB::transaction(function () use ($orderId) {
            $payment = Payment::query()
                ->where('external_reference', $orderId)
                ->where('provider', 'paypal')
                ->first();

            if (! $payment) {
                return ['ok' => false, 'reason' => 'payment_not_found'];
            }

            $alreadyPaid = Payment::query()
                ->where('user_id', $payment->user_id)
                ->where('course_id', $payment->course_id)
                ->where('status', Payment::STATUS_PAID)
                ->exists();

            if ($alreadyPaid) {
                return ['ok' => true, 'reason' => 'already_paid'];
            }

            $payment->status = Payment::STATUS_PAID;
            $payment->save();

            $user = User::query()->find($payment->user_id);
            $course = Course::query()->find($payment->course_id);

            if ($user && $course) {
                $this->enroller->execute($user, $course);
            }

            return ['ok' => true, 'reason' => 'completed'];
        });
    }
}
