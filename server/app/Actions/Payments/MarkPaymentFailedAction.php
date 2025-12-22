<?php

namespace App\Actions\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class MarkPaymentFailedAction
{
    public function execute(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->status = \App\Models\Payment::STATUS_FAILED;
            $payment->save();
        });
    }
}
