<?php

use App\Models\User;

it('renders secure webhook endpoints in dashboard settings when app url uses https', function () {
    config()->set('app.url', 'https://learnova.bakerysoft.net');

    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->actingAs($admin)->get(route('dashboard.settings.edit'));

    $response
        ->assertOk()
        ->assertSee('https://learnova.bakerysoft.net/webhooks/paypal', false)
        ->assertSee('https://learnova.bakerysoft.net/webhooks/stripe', false)
        ->assertDontSee('http://learnova.bakerysoft.net/webhooks/paypal', false);
});
