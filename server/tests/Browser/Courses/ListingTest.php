<?php

namespace Tests\Browser\Courses;

use App\Models\Course;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ListingTest extends DuskTestCase
{
    public function test_open_and_click_course(): void
    {
        Artisan::call('migrate', ['--force' => true]);

        Course::updateOrCreate(['slug' => 'intro-to-laravel'], [
            'title' => 'Intro to Laravel',
            'description' => 'Learn Laravel basics.',
            'thumbnail_path' => null,
            'price' => 0,
            'currency' => 'USD',
            'is_free' => true,
            'status' => \App\Models\Course::STATUS_PUBLISHED,
            'language' => 'en',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/courses')
                ->assertSee('Intro to Laravel')
                ->clickLink('Intro to Laravel')
                ->assertPathIs('/courses/intro-to-laravel')
                ->assertSee('Intro to Laravel');
        });
    }
}
