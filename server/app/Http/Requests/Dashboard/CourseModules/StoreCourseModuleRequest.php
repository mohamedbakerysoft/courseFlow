<?php

namespace App\Http\Requests\Dashboard\CourseModules;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
