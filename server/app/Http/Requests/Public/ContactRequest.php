<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $recaptchaEnabled = (bool) (config('services.recaptcha.enabled') ?? false);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'message' => ['required', 'string', 'max:5000'],
            'captcha_token' => $recaptchaEnabled ? ['required', 'string'] : ['nullable', 'string'],
        ];
    }
}
