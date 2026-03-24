<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class RejectManualPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'review_notes' => ['required', 'string', 'max:2000'],
        ];
    }
}
