<?php

use App\Actions\Pages\ShowPageAction;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('fetches page by slug via action', function () {
    Page::create(['slug' => 'about', 'title' => 'About', 'content' => 'About content']);

    $page = app(ShowPageAction::class)->execute('about');
    expect($page->title)->toBe('About');
});

