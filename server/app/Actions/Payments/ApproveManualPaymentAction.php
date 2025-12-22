<?php

namespace App\Actions\Payments;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ApproveManualPaymentAction
{
    public function __construct(private EnrollUserInCourseAction $enroller) {}

    public function execute(Payment $payment, User $approver): void
    {
        if ($payment->provider !== 'manual') {
            throw new \RuntimeException('Only manual payments can be approved.');
        }

        DB::transaction(function () use ($payment, $approver) {
            $alreadyPaid = Payment::where('user_id', $payment->user_id)
                ->where('course_id', $payment->course_id)
                ->where('status', Payment::STATUS_PAID)
                ->exists();
            if ($alreadyPaid) {
                return;
            }

            $payment->status = Payment::STATUS_PAID;
            $payment->approved_by = $approver->id;
            $payment->approved_at = Carbon::now();
            $payment->save();

            $user = User::find($payment->user_id);
            $course = Course::find($payment->course_id);
            if ($user && $course) {
                $this->enroller->execute($user, $course);
            }
        });
    }
}
