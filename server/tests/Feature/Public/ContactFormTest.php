<?php

use App\Actions\Public\SubmitContactMessageAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows contact form when enabled', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    \Pest\Laravel\actingAs($admin)->post(route('dashboard.settings.update'), [
        'default_language' => 'en',
        'payments_stripe_enabled' => false,
        'payments_paypal_enabled' => false,
        'payments_manual_instructions' => '',
        'landing_show_contact_form' => true,
    ])->assertRedirect();

    $response = \Pest\Laravel\get('/');
    $response->assertOk();
    $response->assertSee('Get in touch');
});

it('hides contact form when disabled', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    \Pest\Laravel\actingAs($admin)->post(route('dashboard.settings.update'), [
        'default_language' => 'en',
        'payments_stripe_enabled' => false,
        'payments_paypal_enabled' => false,
        'payments_manual_instructions' => '',
        'landing_show_contact_form' => false,
    ])->assertRedirect();

    $response = \Pest\Laravel\get('/');
    $response->assertOk();
    $response->assertDontSee('Get in touch');
});

it('submits contact form successfully', function () {
    config()->set('services.recaptcha.enabled', false);
    $response = \Pest\Laravel\post(route('contact.submit'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Hello',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('status', 'Message sent');
});

it('resolves the contact recipient to the current admin email', function () {
    User::factory()->create([
        'name' => 'Owner Admin',
        'email' => 'owner@example.com',
        'role' => User::ROLE_ADMIN,
    ]);

    config()->set('demo.admin_email', 'missing-admin@example.com');

    $action = app(SubmitContactMessageAction::class);

    expect($action->resolveRecipientAddress())->toBe('owner@example.com');
});
