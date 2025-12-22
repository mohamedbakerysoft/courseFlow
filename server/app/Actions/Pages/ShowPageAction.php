<?php

namespace App\Actions\Pages;

use App\Models\Page;

class ShowPageAction
{
    public function execute(string $slug): Page
    {
        return Page::query()->where('slug', $slug)->firstOrFail();
    }
}
