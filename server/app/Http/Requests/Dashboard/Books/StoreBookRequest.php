<?php

namespace App\Http\Requests\Dashboard\Books;

use App\Models\ReferenceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:courses,slug'],
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
