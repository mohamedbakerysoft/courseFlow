<?php

namespace App\Http\Requests\Dashboard\Appearance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppearanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'primary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'secondary' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'accent' => ['required', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ];
    }
}
