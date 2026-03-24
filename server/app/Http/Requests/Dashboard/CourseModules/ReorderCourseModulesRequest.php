<?php

namespace App\Http\Requests\Dashboard\CourseModules;

use Illuminate\Foundation\Http\FormRequest;

class ReorderCourseModulesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'module_ids' => ['required', 'array'],
            'module_ids.*' => ['required', 'integer'],
        ];
    }
}
