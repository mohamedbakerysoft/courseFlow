<?php

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads static pages by slug without auth', function () {
    Page::create(['slug' => 'about', 'title' => 'About', 'content' => 'About page content']);
    Page::create(['slug' => 'terms', 'title' => 'Terms', 'content' => 'Terms content']);
    Page::create(['slug' => 'privacy', 'title' => 'Privacy', 'content' => 'Privacy content']);

    \Pest\Laravel\get('/about')->assertOk()->assertSee('About');
    \Pest\Laravel\get('/terms')->assertOk()->assertSee('Terms');
    \Pest\Laravel\get('/privacy')->assertOk()->assertSee('Privacy');
});

