<?php

use App\Actions\Pages\ShowPageAction;
use App\Models\Setting;

it('fetches legal page content from settings via action', function () {
    Setting::updateOrCreate(['key' => 'site.default_language'], ['value' => 'en']);
    Setting::updateOrCreate(['key' => 'legal.terms.en'], ['value' => 'Action Terms EN']);
    $page = app(ShowPageAction::class)->execute('terms');
    expect($page->title)->toBe('Terms of Service');
    expect($page->content)->toContain('Action Terms EN');
});
