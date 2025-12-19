<?php

namespace Tests\Browser\Courses;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EnrollFlowTest extends DuskTestCase
{
    public function test_enroll_via_login_flow(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        $user = User::updateOrCreate(['email' => 'student@example.com'], [
            'name' => 'Student',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_STUDENT,
        ]);

        Course::updateOrCreate(['slug' => 'free-course'], [
            'title' => 'Free Course',
            'description' => 'Desc',
            'price' => 0,
            'currency' => 'USD',
            'is_free' => true,
            'status' => \App\Models\Course::STATUS_PUBLISHED,
            'language' => 'en',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/courses')
                ->clickLink('Free Course')
                ->assertPathIs('/courses/free-course')
                ->assertSee('Login to Enroll')
                ->clickLink('Login to Enroll')
                ->assertPathIs('/login')
                ->type('email', 'student@example.com')
                ->type('password', 'password')
                ->click('button[type="submit"]')
                ->visit('/courses/free-course')
                ->waitForText('Enroll', 5)
                ->press('Enroll')
                ->waitForText('You are enrolled', 5)
                ->assertSee('You are enrolled');
        });
    }
}
