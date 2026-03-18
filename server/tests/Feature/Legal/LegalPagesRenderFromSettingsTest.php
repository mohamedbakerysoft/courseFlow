<?php

use App\Models\Setting;

use function Pest\Laravel\get;

it('renders terms from settings in English', function () {
    Setting::updateOrCreate(['key' => 'site.default_language'], ['value' => 'en']);
    Setting::updateOrCreate(['key' => 'legal.terms.en'], ['value' => 'Test Terms EN']);
    $response = get('/terms');
    $response->assertStatus(200);
    $response->assertSee('Test Terms EN');
});

it('renders privacy from settings in English', function () {
    Setting::updateOrCreate(['key' => 'site.default_language'], ['value' => 'en']);
    Setting::updateOrCreate(['key' => 'legal.privacy.en'], ['value' => 'Test Privacy EN']);
    $response = get('/privacy');
    $response->assertStatus(200);
    $response->assertSee('Test Privacy EN');
});
