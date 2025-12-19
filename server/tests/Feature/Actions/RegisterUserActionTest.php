<?php

use App\Actions\Auth\RegisterUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a student user via action', function () {
    $action = app(RegisterUserAction::class);
    $user = $action->execute('Action User', 'action@example.com', 'password');

    expect($user)->toBeInstanceOf(User::class);
    expect($user->role)->toBe(User::ROLE_STUDENT);
    expect(User::query()->where('email', 'action@example.com')->exists())->toBeTrue();
});
