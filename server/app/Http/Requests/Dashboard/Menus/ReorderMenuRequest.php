<?php

namespace App\Http\Requests\Dashboard\Menus;

use Illuminate\Foundation\Http\FormRequest;

class ReorderMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'item_keys' => ['required', 'array'],
            'item_keys.*' => ['required', 'string'],
        ];
    }
}
