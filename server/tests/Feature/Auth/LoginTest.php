<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('logs in a student successfully', function () {
    $user = User::factory()->create([
        'email' => 'student_login@example.com',
        'password' => Hash::make('password'),
        'role' => User::ROLE_STUDENT,
    ]);

    $response = \Pest\Laravel\post('/login', [
        'email' => 'student_login@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    \Pest\Laravel\assertAuthenticatedAs($user);
});
