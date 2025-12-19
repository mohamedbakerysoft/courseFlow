<?php

namespace Tests\Browser\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InstructorFlowTest extends DuskTestCase
{
    public function test_instructor_creates_course_adds_lesson_and_publishes(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        $user = User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_ADMIN,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', 'admin@example.com')
                ->type('password', 'password')
                ->click('button[type="submit"]')
                ->visit('/dashboard/courses')
                ->visit('/dashboard/courses/create')
                ->type('title', 'New Dashboard Course')
                ->type('slug', 'new-dashboard-course')
                ->type('description', 'Desc')
                ->type('language', 'en')
                ->waitForText('Create Course', 5)
                ->click('button[type="submit"]')
                ->waitForText('Edit Course', 5)
                ->assertSee('Edit Course')
                ->clickLink('Manage Lessons')
                ->visit('/dashboard/courses/new-dashboard-course/lessons/create')
                ->type('title', 'Lesson One')
                ->type('slug', 'lesson-one')
                ->type('video_url', 'https://www.youtube.com/embed/dQw4w9WgXcQ')
                ->type('position', '1')
                ->click('button[type="submit"]')
                ->waitForText('Edit Lesson', 5)
                ->assertSee('Edit Lesson')
                ->clickLink('Back to Lessons')
                ->clickLink('Back to Course')
                ->waitForText('Edit Course', 5)
                ->assertSee('Edit Course')
                ->press('Publish')
                ->waitForText('Status: Published', 5)
                ->visit('/courses')
                ->waitForText('New Dashboard Course', 10)
                ->assertSee('New Dashboard Course');
        });
    }
}
