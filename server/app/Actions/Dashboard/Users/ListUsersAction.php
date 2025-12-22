<?php

namespace App\Actions\Dashboard\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListUsersAction
{
    public function execute(int $perPage = 20): LengthAwarePaginator
    {
        return User::query()
            ->orderByDesc('created_at')
            ->select(['id', 'name', 'email', 'role', 'is_disabled'])
            ->paginate($perPage);
    }
}
