<?php

use App\Actions\Install\CreateAdminAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('creates admin user', function () {
    $user = app(CreateAdminAction::class)->execute('Admin', 'admin@example.com', 'password123');
    expect($user)->toBeInstanceOf(User::class);
    expect($user->role)->toBe(User::ROLE_ADMIN);
    expect(Hash::check('password123', $user->password))->toBeTrue();
});
