<?php

namespace App\Http\Requests\Dashboard\Books;

use App\Models\Course;
use App\Models\ReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        /** @var Course $book */
        $book = $this->route('book');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('courses', 'slug')->ignore($book?->id)],
            'description' => ['nullable', 'string'],
            'thumbnail_path' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'download_file' => ['nullable', 'file', 'mimes:pdf,epub,zip,doc,docx', 'max:10240'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8', Rule::exists('reference_options', 'code')->where('type', ReferenceOption::TYPE_CURRENCY)->where('is_active', true)],
            'is_free' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', 'max:12', Rule::exists('reference_options', 'code')->where('type', ReferenceOption::TYPE_LANGUAGE)->where('is_active', true)],
            'remove_download_file' => ['nullable', 'boolean'],
        ];
    }
}
