<?php

namespace App\Http\Requests\Dashboard\Lessons;

use App\Models\Lesson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        /** @var Lesson $lesson */
        $lesson = $this->route('lesson');

        return [
            'module_id' => ['required', Rule::exists('course_modules', 'id')->where('course_id', $lesson->course_id)],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('lessons', 'slug')->where('course_id', $lesson->course_id)->ignore($lesson->id),
            ],
            'video_url' => ['nullable', 'url'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4', 'max:102400'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            /** @var Lesson $lesson */
            $lesson = $this->route('lesson');

            if (! $this->filled('video_url') && ! $this->hasFile('video_file') && ! filled($lesson->video_file_path)) {
                $validator->errors()->add('video_url', __('Add a YouTube/video URL or upload an MP4 file.'));
            }

            if ($this->filled('video_url') && $this->hasFile('video_file')) {
                $validator->errors()->add('video_url', __('Choose either a video URL or an MP4 upload, not both.'));
            }
        });
    }
}
