<?php

namespace Tests\Browser\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SettingsTabsTest extends DuskTestCase
{
    public function test_tabs_switch_and_save_independently(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_ADMIN,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'admin@example.com')
                ->type('password', 'password')
                ->click('button[type="submit"]')
                ->visit('/dashboard/settings')
                ->assertSee('Settings')
                ->click('a[href="#payments"]')
                ->waitFor('form[x-show="tab === \'payments\'"]', 5)
                ->check('payments_stripe_enabled')
                ->select('stripe_mode', 'test')
                ->type('stripe_publishable_key', 'pk_test_1234567890abcd')
                ->type('stripe_secret_key', 'sk_test_1234567890abcd')
                ->type('stripe_webhook_secret', 'whsec_123456')
                ->click('button[type="submit"]')
                ->visit('/dashboard/settings')
                ->click('a[href="#general"]')
                ->waitFor('form[x-show="tab === \'general\'"]', 5)
                ->select('default_language', 'ar')
                ->click('button[type="submit"]')
                ->visit('/dashboard/settings')
                ->visit('/dashboard/settings')
                ->click('a[href="#general"]')
                ->waitFor('select#default_language', 5)
                ->assertSelected('select#default_language', 'ar')
                ->visit('/dashboard/settings')
                ->click('a[href="#payments"]')
                ->waitFor('input#stripe_webhook_endpoint', 5)
                ->click('button[aria-label="Copy"]')
                ->waitForText('Copied', 5)
                ->assertSee('Copied');
        });
    }
}
