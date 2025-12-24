<?php

namespace Tests\Browser\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HeroImageSettingsTest extends DuskTestCase
{
    public function test_upload_preview_save_and_remove(): void
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
                ->click('a[href="#landing"]')
                ->waitFor('form[x-show="tab === \'landing\'"]', 5)
                ->attach('hero_image', public_path('images/demo/Subject.png'))
                ->press('Save Landing Settings')
                ->visit('/')
                ->assertPresent('img[src*="storage/hero/"]')
                ->visit('/dashboard/settings')
                ->click('a[href="#landing"]')
                ->waitFor('form[x-show="tab === \'landing\'"]', 5)
                ->check('remove_hero_image')
                ->press('Save Landing Settings')
                ->visit('/')
                ->assertPresent('img[src*="/images/demo/"]');
        });
    }

    public function test_typography_sliders_update_hero(): void
    {
        Artisan::call('demo:reseed-after-tests', ['--force-testing' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login as Admin')
                ->click('[data-test="demo-admin"]')
                ->assertPathIs('/dashboard')
                ->visit('/dashboard/settings')
                ->click('a[href="#landing"]')
                ->waitFor('form[x-show="tab === \'landing\'"]', 5)
                ->script([
                    "var el=document.getElementById('hero_font_title'); if(el){ el.value=72; el.dispatchEvent(new Event('input',{bubbles:true})); el.dispatchEvent(new Event('change',{bubbles:true})); }",
                    "var el2=document.getElementById('hero_font_subtitle'); if(el2){ el2.value=32; el2.dispatchEvent(new Event('input',{bubbles:true})); el2.dispatchEvent(new Event('change',{bubbles:true})); }",
                    "var el3=document.getElementById('hero_font_description'); if(el3){ el3.value=20; el3.dispatchEvent(new Event('input',{bubbles:true})); el3.dispatchEvent(new Event('change',{bubbles:true})); }",
                ]);
            $browser->pause(200)
                ->press('Save Landing Settings')
                ->visit('/')
                ->assertPresent('h2[style*="var(--hero-title-size)"]')
                ->assertSourceHas('--hero-title-size: 72px')
                ->assertSourceHas('--hero-subtitle-size: 32px')
                ->assertSourceHas('--hero-description-size: 20px');
        });
    }
}
