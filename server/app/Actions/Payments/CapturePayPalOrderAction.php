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

    public function execute(string $orderId): array
    {
        return DB::transaction(function () use ($orderId) {
            $payment = Payment::where('external_reference', $orderId)
                ->where('provider', 'paypal')
                ->first();
            if (! $payment) {
                return ['ok' => false, 'reason' => 'payment_not_found'];
            }

            $alreadyPaid = Payment::where('user_id', $payment->user_id)
                ->where('course_id', $payment->course_id)
                ->where('status', Payment::STATUS_PAID)
                ->exists();
            if ($alreadyPaid) {
                return ['ok' => true, 'status' => 'COMPLETED', 'reason' => 'already_paid'];
            }

            $result = $this->paypal->captureOrder($orderId);
            if (($result['status'] ?? '') !== 'COMPLETED') {
                if ($this->isTerminalCaptureFailure($result)) {
                    $payment->status = Payment::STATUS_FAILED;
                    $payment->save();
                }

                return [
                    'ok' => false,
                    'reason' => 'capture_incomplete',
                    'status' => (string) ($result['status'] ?? ''),
                    'http_status' => (int) ($result['http_status'] ?? 0),
                ];
            }

            $payment->status = Payment::STATUS_PAID;
            $payment->save();

            $user = User::find($payment->user_id);
            $course = Course::find($payment->course_id);
            if ($user && $course) {
                $this->enroller->execute($user, $course);
            }

            return ['ok' => true, 'status' => 'COMPLETED'];
        });
    }

    private function isTerminalCaptureFailure(array $result): bool
    {
        return (int) ($result['http_status'] ?? 0) === 422;
    }
}
