<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registers a student successfully', function () {
    $response = \Pest\Laravel\post('/register', [
        'name' => 'Student One',
        'email' => 'student1@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/dashboard');

    $user = User::query()->where('email', 'student1@example.com')->first();
    expect($user)->not()->toBeNull();
    expect($user->role)->toBe(User::ROLE_STUDENT);
});
