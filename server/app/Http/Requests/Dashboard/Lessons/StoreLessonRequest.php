<?php

namespace App\Http\Requests\Dashboard\Lessons;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        /** @var Course $course */
        $course = $this->route('course');

        return [
            'module_id' => ['required', Rule::exists('course_modules', 'id')->where('course_id', $course->id)],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('lessons', 'slug')->where('course_id', $course->id),
            ],
            'video_url' => ['nullable', 'url'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4', 'max:102400'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('video_url') && ! $this->hasFile('video_file')) {
                $validator->errors()->add('video_url', __('Add a YouTube/video URL or upload an MP4 file.'));
            }

            if ($this->filled('video_url') && $this->hasFile('video_file')) {
                $validator->errors()->add('video_url', __('Choose either a video URL or an MP4 upload, not both.'));
            }
        });
    }
}
