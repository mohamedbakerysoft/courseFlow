<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function delete(User $actor, User $user): bool
    {
        if ($user->is_demo) {
            return false;
        }
        return $actor->id === $user->id;
    }
}
