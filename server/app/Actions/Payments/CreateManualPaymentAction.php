<?php

namespace App\Actions\Payments;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateManualPaymentAction
{
    public function execute(User $user, Course $course): Payment
    {
        if ($course->is_free || (float) $course->price <= 0.0) {
            throw new \RuntimeException('Course is free; manual payment not applicable.');
        }
        return DB::transaction(function () use ($user, $course) {
            return Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'provider' => 'manual',
                'amount' => (float) $course->price,
                'currency' => $course->currency ?? 'USD',
                'status' => Payment::STATUS_PENDING,
                'external_reference' => 'manual_' . Str::random(10),
            ]);
        });
    }
}

