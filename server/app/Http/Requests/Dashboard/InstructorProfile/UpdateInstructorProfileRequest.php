<?php

namespace App\Http\Requests\Dashboard\InstructorProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'instructor_name' => ['nullable', 'string', 'max:255'],
            'instructor_title' => ['nullable', 'string', 'max:255'],
            'instructor_bio' => ['nullable', 'string'],
            'hero_headline' => ['nullable', 'string', 'max:255'],
            'hero_subheadline' => ['nullable', 'string'],
            'page_label' => ['nullable', 'string', 'max:255'],
            'catalog_label' => ['nullable', 'string', 'max:255'],
            'catalog_heading' => ['nullable', 'string', 'max:255'],
            'catalog_description' => ['nullable', 'string'],
            'primary_cta_label' => ['nullable', 'string', 'max:255'],
            'secondary_cta_label' => ['nullable', 'string', 'max:255'],
            'best_for_text' => ['nullable', 'string', 'max:255'],
            'focus_areas' => ['nullable', 'string'],
            'expectations_label' => ['nullable', 'string', 'max:255'],
            'expectations_heading' => ['nullable', 'string', 'max:255'],
            'expectations' => ['nullable', 'string'],
            'social_website' => ['nullable', 'url'],
            'social_twitter' => ['nullable', 'url'],
            'social_instagram' => ['nullable', 'url'],
            'social_youtube' => ['nullable', 'url'],
            'social_linkedin' => ['nullable', 'url'],
            'social_facebook' => ['nullable', 'url'],
            'instructor_image' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
