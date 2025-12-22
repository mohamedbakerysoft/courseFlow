<?php

namespace Tests\Browser\Legal;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FooterLinksTest extends DuskTestCase
{
    public function test_terms_and_privacy_links_are_accessible(): void
    {
        Artisan::call('demo:reseed-after-tests', ['--force-testing' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPresent('footer a[href="/terms"]')
                ->assertPresent('footer a[href="/privacy"]')
                ->click('footer a[href="/terms"]')
                ->assertPathIs('/terms')
                ->visit('/')
                ->click('footer a[href="/privacy"]')
                ->assertPathIs('/privacy');
        });
    }
}
