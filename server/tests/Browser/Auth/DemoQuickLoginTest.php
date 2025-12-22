<?php

namespace Tests\Browser\Auth;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DemoQuickLoginTest extends DuskTestCase
{
    public function test_login_as_admin_one_click(): void
    {
        Artisan::call('demo:reseed-after-tests', ['--force-testing' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login as Admin')
                ->click('[data-test="demo-admin"]')
                ->assertPathIs('/dashboard');
        });
    }

    public function test_login_as_student_one_click(): void
    {
        Artisan::call('demo:reseed-after-tests', ['--force-testing' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login as Student')
                ->click('[data-test="demo-student"]')
                ->assertPathIs('/dashboard');
        });
    }
}
