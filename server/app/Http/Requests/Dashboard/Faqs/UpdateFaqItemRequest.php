<?php

namespace App\Http\Requests\Dashboard\Faqs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }
}
