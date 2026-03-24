<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\GetDashboardWorkspaceAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(GetDashboardWorkspaceAction $action): View
    {
        $user = Auth::user();

        return view('dashboard', $action->execute($user));
    }
}
