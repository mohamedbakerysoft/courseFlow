<?php

namespace App\Actions\Install;

use App\Models\Course;
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

            $this->assignOrphanedContentTo($user);

            return $user;
        }

        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assignOrphanedContentTo($user);

        return $user;
    }

    private function assignOrphanedContentTo(User $user): void
    {
        Course::query()
            ->whereNull('instructor_id')
            ->update(['instructor_id' => $user->id]);
    }
}
