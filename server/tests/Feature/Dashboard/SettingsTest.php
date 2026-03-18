<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('instructor can view settings page', function () {
    $instructor = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $this->actingAs($instructor);

    $response = $this->get(route('dashboard.settings.edit'));

    $response->assertOk();
    $response->assertSee('Storefront language');
});

it('student cannot access settings page', function () {
    $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
    $this->actingAs($student);

    $response = $this->get(route('dashboard.settings.edit'));

    $response->assertForbidden();
});

it('instructor can update settings including logo and payments', function () {
    Storage::fake('public');

    $instructor = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $this->actingAs($instructor);

    $logo = UploadedFile::fake()->image('logo.png', 200, 200);

    $response = $this->post(route('dashboard.settings.update'), [
        'logo' => $logo,
        'payments_stripe_enabled' => '1',
        'payments_paypal_enabled' => '0',
        'payments_manual_instructions' => 'Bank transfer details',
    ]);

    $response->assertRedirect();

    $settings = Setting::query()->pluck('value', 'key');

    expect($settings['site.default_language'] ?? null)->toBe('en');
    expect((bool) ($settings['payments.stripe.enabled'] ?? false))->toBeTrue();
    expect((bool) ($settings['payments.paypal.enabled'] ?? false))->toBeFalse();
    expect($settings['payments.manual.instructions'] ?? null)->toBe('Bank transfer details');

    $logoPath = $settings['site.logo_path'] ?? null;
    expect($logoPath)->not->toBeNull();
    Storage::disk('public')->assertExists($logoPath);
});

it('keeps public pages in english without rtl direction', function () {
    Setting::updateOrCreate(
        ['key' => 'site.default_language'],
        ['value' => 'en'],
    );

    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('lang="en"', false);
    $response->assertDontSee('dir="rtl"', false);
});
