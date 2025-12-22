<?php

namespace App\Actions\Dashboard\Users;

use App\Models\User;

class UpdateUserStatusAction
{
    public function execute(User $user, bool $disabled): User
    {
        $user->is_disabled = $disabled;
        $user->save();

        return $user;
    }
}
