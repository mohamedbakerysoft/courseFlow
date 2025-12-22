<?php

namespace App\Actions\Dashboard\Courses;

use Illuminate\Http\UploadedFile;

class UploadCourseThumbnailAction
{
    public function execute(UploadedFile $file): string
    {
        $path = $file->store('thumbnails', 'public');

        return 'storage/'.$path;
    }
}
