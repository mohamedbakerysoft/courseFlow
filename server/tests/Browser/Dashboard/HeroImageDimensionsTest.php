<?php

namespace Tests\Browser\Dashboard;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HeroImageDimensionsTest extends DuskTestCase
{
    public function test_set_dimensions_and_reflect_on_landing(): void
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
                ->type('hero_image_width', '640')
                ->type('hero_image_height', '400')
                ->press('Save Landing Settings')
                ->visit('/')
                ->assertPresent('img[alt="Hero Image"]')
                ->assertSourceHas('width: 640px')
                ->assertSourceHas('height: 400px');
        });
    }
}

