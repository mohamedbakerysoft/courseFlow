<?php

use Illuminate\Support\Facades\Config;

use function Pest\Laravel\get;

it('shows demo login buttons when demo enabled', function () {
    Config::set('demo.enabled', true);
    $response = get('/login');
    $response->assertStatus(200);
    $response->assertSee('Fill Admin Demo');
    $response->assertSee('Fill Student Demo');
    $response->assertDontSee('Login as Instructor');
    $response->assertSee('Demo mode can prefill the demo credentials for quick testing, while still requiring a normal login submit.');
});

it('hides demo login buttons when demo disabled', function () {
    Config::set('demo.enabled', false);
    $response = get('/login');
    $response->assertStatus(200);
    $response->assertDontSee('Fill Admin Demo');
    $response->assertDontSee('Fill Student Demo');
});
