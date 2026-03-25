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

    public function execute(Payment $payment, User $approver): array
    {
        if ($payment->provider !== 'manual') {
            throw new \RuntimeException('Only manual payments can be approved.');
        }

        if (! $payment->is_manual_submission_complete) {
            throw new \RuntimeException('Manual payment proof is incomplete.');
        }

        return DB::transaction(function () use ($payment, $approver) {
            $alreadyPaid = Payment::where('user_id', $payment->user_id)
                ->where('course_id', $payment->course_id)
                ->where('status', Payment::STATUS_PAID)
                ->exists();
            if ($alreadyPaid) {
                $this->reconcileAlreadyPaidManualRequest($payment);
                $this->enrollPaymentUser($payment);

                return ['result' => 'already_paid_reconciled'];
            }

            $payment->status = Payment::STATUS_PAID;
            $payment->approved_by = $approver->id;
            $payment->approved_at = Carbon::now();
            $payment->rejected_at = null;
            $payment->save();

            $this->enrollPaymentUser($payment);

            return ['result' => 'approved'];
        });
    }

    private function enrollPaymentUser(Payment $payment): void
    {
        $user = User::find($payment->user_id);
        $course = Course::find($payment->course_id);
        if ($user && $course) {
            $this->enroller->execute($user, $course);
        }
    }

    private function reconcileAlreadyPaidManualRequest(Payment $payment): void
    {
        $payment->status = Payment::STATUS_FAILED;
        $payment->review_notes = __('Access was already granted by another completed payment.');
        $payment->rejected_at = Carbon::now();
        $payment->approved_by = null;
        $payment->approved_at = null;
        $payment->save();
    }
}
