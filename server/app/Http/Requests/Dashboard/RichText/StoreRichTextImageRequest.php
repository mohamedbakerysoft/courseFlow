<?php

namespace App\Http\Requests\Dashboard\RichText;

use Illuminate\Foundation\Http\FormRequest;

class StoreRichTextImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'max:4096'],
        ];
    }
}
