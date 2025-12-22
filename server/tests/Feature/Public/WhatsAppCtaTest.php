<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows whatsapp cta when enabled', function () {
    \App\Models\Setting::updateOrCreate(['key' => 'contact.whatsapp.enabled'], ['value' => true]);
    \App\Models\Setting::updateOrCreate(['key' => 'contact.whatsapp.phone'], ['value' => '+201234567890']);
    \App\Models\Setting::updateOrCreate(['key' => 'contact.whatsapp.message'], ['value' => 'Hello from test']);

    $response = $this->get('/');
    $response->assertOk();
    $response->assertSee('wa.me/201234567890');
});

it('hides whatsapp cta when disabled', function () {
    \App\Models\Setting::updateOrCreate(['key' => 'contact.whatsapp.enabled'], ['value' => false]);
    \App\Models\Setting::updateOrCreate(['key' => 'contact.whatsapp.phone'], ['value' => '+201234567890']);

    $response = $this->get('/');
    $response->assertOk();
    $response->assertDontSee('wa.me/');
});
