<?php

namespace App\Actions\Payments;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Carbon;

class RejectManualPaymentAction
{
    public function execute(Payment $payment, ?User $reviewer, string $reviewNotes): void
    {
        Payment::query()
            ->where('user_id', $payment->user_id)
            ->where('course_id', $payment->course_id)
            ->where('status', Payment::STATUS_FAILED)
            ->whereKeyNot($payment->id)
            ->delete();

        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'approved_by' => $reviewer?->id,
            'review_notes' => $reviewNotes,
            'rejected_at' => Carbon::now(),
        ]);
    }
}
