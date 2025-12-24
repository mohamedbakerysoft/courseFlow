<?php

namespace App\Actions\Install;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminAction
{
    public function execute(string $name, string $email, string $password): User
    {
        $user = User::query()->where('email', $email)->first();
        if ($user) {
            $user->name = $name;
            $user->password = Hash::make($password);
            $user->role = User::ROLE_ADMIN;
            $user->save();

            return $user;
        }

        return User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
