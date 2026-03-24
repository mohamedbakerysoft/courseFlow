<?php

namespace App\Http\Requests\Dashboard\Faqs;

use Illuminate\Foundation\Http\FormRequest;

class ReorderFaqItemsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'faq_ids' => ['required', 'array'],
            'faq_ids.*' => ['required', 'integer', 'exists:faq_items,id'],
        ];
    }
}
