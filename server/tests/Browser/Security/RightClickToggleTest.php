<?php

namespace Tests\Browser\Security;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RightClickToggleTest extends DuskTestCase
{
    public function test_right_click_disabled_on_home_when_setting_false(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        Setting::updateOrCreate(['key' => 'security.right_click.enabled'], ['value' => false]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSourceHas('oncontextmenu="return false"');
        });
    }

    public function test_right_click_enabled_on_home_when_setting_true(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        Setting::updateOrCreate(['key' => 'security.right_click.enabled'], ['value' => true]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSourceMissing('oncontextmenu="return false"');
        });
    }
}
