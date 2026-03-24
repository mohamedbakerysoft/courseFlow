<?php

namespace App\Http\Requests\Dashboard\Faqs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqPageSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading' => ['required', 'string', 'max:255'],
            'subheading' => ['nullable', 'string'],
        ];
    }
}
