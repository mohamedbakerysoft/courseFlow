<?php

use Illuminate\Support\Facades\Config;

use function Pest\Laravel\get;

it('shows demo login buttons when demo enabled', function () {
    Config::set('demo.enabled', true);
    $response = get('/login');
    $response->assertStatus(200);
    $response->assertSee('Login as Admin');
    $response->assertSee('Login as Student');
    $response->assertSee('Demo credentials are pre-filled for quick exploration.');
});

it('hides demo login buttons when demo disabled', function () {
    Config::set('demo.enabled', false);
    $response = get('/login');
    $response->assertStatus(200);
    $response->assertDontSee('Login as Admin');
    $response->assertDontSee('Login as Student');
});
