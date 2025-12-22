<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(\App\Services\SettingsService $settings): View
    {
        $googleLoginEnabled = (bool) $settings->get('auth.google.enabled', false);

        return view('auth.register', compact('googleLoginEnabled'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request, RegisterUserAction $registerUser, \App\Actions\Security\ValidateRecaptchaAction $captcha): RedirectResponse
    {
        $ok = $captcha->execute($request->string('captcha_token')->toString());
        if (! $ok) {
            return back()->withErrors(['captcha' => __('Captcha verification failed')])->withInput();
        }

        $user = $registerUser->execute(
            $request->string('name')->toString(),
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
