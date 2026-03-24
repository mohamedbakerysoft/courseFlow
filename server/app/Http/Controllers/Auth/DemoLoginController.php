<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\DemoLoginAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DemoLoginController extends Controller
{
    public function __invoke(string $who, DemoLoginAction $action): RedirectResponse
    {
        return $action->execute($who);
    }
}
