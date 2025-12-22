<?php

namespace App\Actions\Payments;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HandleStripePaymentSuccessAction
{
    public function __construct(private EnrollUserInCourseAction $enroller) {}

    public function execute(array $session): void
    {
        $sessionId = $session['id'] ?? null;
        $metadata = $session['metadata'] ?? [];
        $userId = (int) ($metadata['user_id'] ?? 0);
        $courseId = (int) ($metadata['course_id'] ?? 0);

        if (! $sessionId || ! $userId || ! $courseId) {
            throw new \RuntimeException('Invalid session payload.');
        }

        DB::transaction(function () use ($sessionId, $userId, $courseId) {
            $payment = Payment::where('stripe_session_id', $sessionId)->first();
            if (! $payment) {
                // Create record if it doesn't exist (idempotency safeguard)
                $payment = Payment::create([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                    'provider' => 'stripe',
                    'amount' => Course::find($courseId)?->price ?? 0,
                    'currency' => Course::find($courseId)?->currency ?? 'USD',
                    'status' => Payment::STATUS_PENDING,
                    'stripe_session_id' => $sessionId,
                ]);
            }

            // Prevent duplicates
            $alreadyPaid = Payment::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->where('status', Payment::STATUS_PAID)
                ->exists();
            if ($alreadyPaid) {
                return;
            }

            $payment->status = Payment::STATUS_PAID;
            $payment->save();

            // Enroll user after successful payment
            $user = User::find($userId);
            $course = Course::find($courseId);
            if ($user && $course) {
                $this->enroller->execute($user, $course);
            }
        });
    }
}
