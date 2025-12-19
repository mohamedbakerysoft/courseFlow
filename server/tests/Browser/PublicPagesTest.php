<?php

namespace Tests\Browser;

use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PublicPagesTest extends DuskTestCase
{
    public function test_instructor_and_about_visible(): void
    {
        Artisan::call('migrate', ['--force' => true]);

        User::firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_ADMIN,
            'bio' => 'Instructor bio',
        ]);

        Page::updateOrCreate(['slug' => 'about'], [
            'title' => 'About the Instructor',
            'content' => 'About page content',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/instructor')
                ->assertSee('Admin')
                ->visit('/about')
                ->assertSee('About the Instructor');
        });
    }
}
