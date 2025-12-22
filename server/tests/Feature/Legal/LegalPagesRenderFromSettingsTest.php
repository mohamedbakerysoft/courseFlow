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

it('renders privacy from settings in Arabic', function () {
    Setting::updateOrCreate(['key' => 'site.default_language'], ['value' => 'ar']);
    Setting::updateOrCreate(['key' => 'legal.privacy.ar'], ['value' => 'سياسة خصوصية اختبار']);
    $response = get('/privacy');
    $response->assertStatus(200);
    $response->assertSee('سياسة خصوصية اختبار');
});
