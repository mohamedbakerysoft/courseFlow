<?php

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('disables right click when setting is false', function () {
    Setting::updateOrCreate(['key' => 'security.right_click.enabled'], ['value' => false]);
    $response = get('/');
    $response->assertStatus(200);
    $response->assertSee('oncontextmenu="return false"', false);
});

it('allows right click when setting is true', function () {
    Setting::updateOrCreate(['key' => 'security.right_click.enabled'], ['value' => true]);
    $response = get('/');
    $response->assertStatus(200);
    $response->assertDontSee('oncontextmenu="return false"', false);
});
