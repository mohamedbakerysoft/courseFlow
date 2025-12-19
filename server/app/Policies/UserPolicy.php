<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function delete(User $actor, User $user): bool
    {
        if ($user->email === config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL)) {
            return false;
        }
        return $actor->id === $user->id;
    }
}
