<?php

namespace Tests\Browser\Auth;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DemoQuickLoginTest extends DuskTestCase
{
    public function test_demo_admin_button_prefills_and_logs_in(): void
    {
        Artisan::call('demo:reseed-after-tests', ['--force-testing' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Fill Admin Demo')
                ->click('[data-test="demo-admin"]')
                ->assertInputValue('email', config('demo.admin_email', 'admin@example.com'))
                ->assertInputValue('password', config('demo.admin_password', 'password'))
                ->press('Log in')
                ->assertPathIs('/dashboard');
        });
    }

    public function test_demo_student_button_prefills_and_logs_in(): void
    {
        Artisan::call('demo:reseed-after-tests', ['--force-testing' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Fill Student Demo')
                ->click('[data-test="demo-student"]')
                ->assertInputValue('email', config('demo.student_email', 'student@demo.com'))
                ->assertInputValue('password', config('demo.student_password', 'password'))
                ->press('Log in')
                ->assertPathIs('/dashboard');
        });
    }
}
