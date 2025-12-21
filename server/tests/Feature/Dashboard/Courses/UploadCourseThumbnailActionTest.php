<?php

use App\Actions\Dashboard\Courses\UploadCourseThumbnailAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores uploaded thumbnail and returns public path', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->image('thumb.jpg', 400, 300);

    $action = new UploadCourseThumbnailAction();
    $stored = $action->execute($file);

    expect($stored)->toStartWith('storage/thumbnails/');
    $relative = str_replace('storage/', '', $stored);
    expect(Storage::disk('public')->exists($relative))->toBeTrue();
});
