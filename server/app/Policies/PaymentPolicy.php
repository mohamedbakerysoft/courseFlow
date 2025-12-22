<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function approve(User $user, Payment $payment): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }
}
