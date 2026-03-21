<?php

namespace App\Actions\Dashboard\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListUsersAction
{
    public function execute(int $perPage = \App\Models\Course::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return User::paginateForDashboard($perPage);
    }
}
