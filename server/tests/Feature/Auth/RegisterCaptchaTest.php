<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registers without captcha in testing even if enabled', function () {
    \App\Models\Setting::updateOrCreate(['key' => 'security.recaptcha.enabled'], ['value' => true]);
    \App\Models\Setting::updateOrCreate(['key' => 'security.recaptcha.site_key'], ['value' => 'site_key']);
    \App\Models\Setting::updateOrCreate(['key' => 'security.recaptcha.secret_key'], ['value' => 'secret_key']);

    $response = $this->post('/register', [
        'name' => 'Tester',
        'email' => 'tester@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        // intentionally no captcha_token
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
    $user = User::query()->where('email', 'tester@example.com')->first();
    expect($user)->not()->toBeNull();
});
