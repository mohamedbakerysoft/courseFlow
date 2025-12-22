<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $recaptchaEnabled = (bool) (config('services.recaptcha.enabled') ?? false);
        $skipCaptcha = app()->environment(['testing', 'dusk', 'dusk.local']);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'captcha_token' => $recaptchaEnabled && ! $skipCaptcha ? ['required', 'string'] : ['nullable', 'string'],
        ];
    }
}
