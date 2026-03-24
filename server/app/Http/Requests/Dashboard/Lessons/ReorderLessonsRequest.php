<?php

namespace App\Http\Requests\Dashboard\Lessons;

use Illuminate\Foundation\Http\FormRequest;

class ReorderLessonsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'modules' => ['nullable', 'array'],
            'modules.*.module_id' => ['nullable', 'integer'],
            'modules.*.lesson_ids' => ['nullable', 'array'],
            'modules.*.lesson_ids.*' => ['integer'],
        ];
    }
}
