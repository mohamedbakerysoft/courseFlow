<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('assigns student role on registration', function () {
    \Pest\Laravel\post('/register', [
        'name' => 'Role Test',
        'email' => 'roletest@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect('/dashboard');

    $user = User::query()->where('email', 'roletest@example.com')->firstOrFail();
    expect($user->role)->toBe(User::ROLE_STUDENT);
});

it('seeds admin user with admin role', function () {
    \Pest\Laravel\artisan('db:seed')->run();

    $admin = User::query()->where('email', 'admin@example.com')->first();
    expect($admin)->not()->toBeNull();
    expect($admin->role)->toBe(User::ROLE_ADMIN);
});
