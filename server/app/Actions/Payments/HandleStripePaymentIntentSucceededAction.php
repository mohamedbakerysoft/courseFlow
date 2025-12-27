<?php

namespace App\Actions\Payments;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HandleStripePaymentIntentSucceededAction
{
    public function __construct(private EnrollUserInCourseAction $enroller) {}

    public function execute(array $intent): void
    {
        $intentId = (string) ($intent['id'] ?? '');
        $metadata = (array) ($intent['metadata'] ?? []);
        $userId = (int) ($metadata['user_id'] ?? 0);
        $courseId = (int) ($metadata['course_id'] ?? 0);

        if ($intentId === '' || $userId <= 0 || $courseId <= 0) {
            throw new \RuntimeException('Invalid intent payload.');
        }

        DB::transaction(function () use ($intentId, $userId, $courseId) {
            $alreadyPaid = Payment::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->where('status', Payment::STATUS_PAID)
                ->exists();
            if ($alreadyPaid) {
                return;
            }

            $payment = Payment::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->where('provider', 'stripe')
                ->first();

            if (! $payment) {
                $course = Course::find($courseId);
                $payment = Payment::create([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                    'provider' => 'stripe',
                    'amount' => $course?->price ?? 0,
                    'currency' => $course?->currency ?? 'USD',
                    'status' => Payment::STATUS_PENDING,
                ]);
            }

            $payment->status = Payment::STATUS_PAID;
            $payment->external_reference = $intentId;
            $payment->save();

            $user = User::find($userId);
            $course = Course::find($courseId);
            if ($user && $course) {
                $this->enroller->execute($user, $course);
            }
        });
    }
}
