<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(SettingsService $settings): RedirectResponse
    {
        $enabled = (bool) $settings->get('auth.google.enabled', false);
        if (! $enabled) {
            abort(404);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(RegisterUserAction $registerUser, SettingsService $settings): RedirectResponse
    {
        $enabled = (bool) $settings->get('auth.google.enabled', false);
        if (! $enabled) {
            abort(404);
        }

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['oauth' => __('Google login failed. Please try again.')]);
        }

        $email = (string) ($googleUser->getEmail() ?? '');
        $name = (string) ($googleUser->getName() ?? 'Student');

        if ($email === '') {
            return redirect()->route('login')->withErrors(['oauth' => __('Google account has no email.')]);
        }

        $user = User::findByEmail($email);
        if (! $user) {
            $user = $registerUser->execute($name, $email, bin2hex(random_bytes(10)));
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}
