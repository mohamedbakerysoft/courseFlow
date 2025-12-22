<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DemoLoginAction
{
    public function execute(string $who): RedirectResponse
    {
        if (! config('demo.enabled')) {
            abort(404);
        }

        $email = $who === 'student'
            ? 'student@demo.com'
            : config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL);

        $user = User::where('email', $email)->firstOrFail();

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
