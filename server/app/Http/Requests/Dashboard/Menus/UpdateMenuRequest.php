<?php

namespace App\Http\Requests\Dashboard\Menus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.key' => ['required', 'string'],
            'items.*.label' => ['required', 'string', 'max:255'],
            'items.*.is_enabled' => ['nullable', 'boolean'],
        ];
    }
}
