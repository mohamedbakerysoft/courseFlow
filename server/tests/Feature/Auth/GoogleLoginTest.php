<?php

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;

it('hides google button when disabled', function () {
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.enabled'], ['value' => false]);

    $response = $this->get('/login');
    $response->assertOk();
    $response->assertDontSee('Continue with Google');
});

it('shows google button when enabled', function () {
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.enabled'], ['value' => true]);
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.client_id'], ['value' => 'id']);
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.client_secret'], ['value' => 'secret']);

    $response = $this->get('/login');
    $response->assertOk();
    $response->assertSee('Continue with Google');
});

it('logs in existing user via google', function () {
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.enabled'], ['value' => true]);
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.client_id'], ['value' => 'id']);
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.client_secret'], ['value' => 'secret']);

    $user = User::factory()->create(['email' => 'existing@example.com']);

    $mockGoogleUser = new class implements SocialiteUserContract
    {
        public function getId()
        {
            return 'gid';
        }

        public function getNickname()
        {
            return null;
        }

        public function getName()
        {
            return 'Existing User';
        }

        public function getEmail()
        {
            return 'existing@example.com';
        }

        public function getAvatar()
        {
            return null;
        }

        public function setRaw(array $user) {}

        public function getRaw()
        {
            return [];
        }

        public function map(array $user)
        {
            return $this;
        }

        public function user()
        {
            return $this;
        }

        public function accessTokenResponseBody()
        {
            return [];
        }

        public function refreshTokenExpires()
        {
            return null;
        }

        public function getApprovedScopes()
        {
            return [];
        }

        public function getRefreshToken()
        {
            return null;
        }

        public function getExpiresIn()
        {
            return null;
        }

        public function getToken()
        {
            return 'token';
        }
    };

    Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
    Socialite::shouldReceive('stateless')->andReturnSelf();
    Socialite::shouldReceive('user')->andReturn($mockGoogleUser);

    $response = $this->get('/auth/google/callback');
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user->fresh());
});

it('registers new user via google', function () {
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.enabled'], ['value' => true]);
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.client_id'], ['value' => 'id']);
    \App\Models\Setting::updateOrCreate(['key' => 'auth.google.client_secret'], ['value' => 'secret']);

    $mockGoogleUser = new class implements SocialiteUserContract
    {
        public function getId()
        {
            return 'gid2';
        }

        public function getNickname()
        {
            return null;
        }

        public function getName()
        {
            return 'New User';
        }

        public function getEmail()
        {
            return 'new@example.com';
        }

        public function getAvatar()
        {
            return null;
        }

        public function setRaw(array $user) {}

        public function getRaw()
        {
            return [];
        }

        public function map(array $user)
        {
            return $this;
        }

        public function user()
        {
            return $this;
        }

        public function accessTokenResponseBody()
        {
            return [];
        }

        public function refreshTokenExpires()
        {
            return null;
        }

        public function getApprovedScopes()
        {
            return [];
        }

        public function getRefreshToken()
        {
            return null;
        }

        public function getExpiresIn()
        {
            return null;
        }

        public function getToken()
        {
            return 'token';
        }
    };

    Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
    Socialite::shouldReceive('stateless')->andReturnSelf();
    Socialite::shouldReceive('user')->andReturn($mockGoogleUser);

    $response = $this->get('/auth/google/callback');
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
    $created = User::query()->where('email', 'new@example.com')->first();
    expect($created)->not()->toBeNull();
    expect($created->role)->toBe(User::ROLE_STUDENT);
});
