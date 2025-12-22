<?php

namespace Tests\Browser\Lessons;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProgressFlowTest extends DuskTestCase
{
    public function test_progress_updates_after_opening_lesson(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        $user = User::updateOrCreate(['email' => 'progress@example.com'], [
            'name' => 'Progress Student',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_STUDENT,
        ]);

        $course = Course::updateOrCreate(['slug' => 'progress-course'], [
            'title' => 'Progress Course',
            'description' => 'Track progress',
            'price' => 0,
            'currency' => 'USD',
            'is_free' => true,
            'status' => \App\Models\Course::STATUS_PUBLISHED,
            'language' => 'en',
        ]);

        Lesson::updateOrCreate(['course_id' => $course->id, 'slug' => 'intro'], [
            'title' => 'Intro',
            'description' => 'Start',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'position' => 1,
            'status' => \App\Models\Lesson::STATUS_PUBLISHED,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'progress@example.com')
                ->type('password', 'password')
                ->click('button[type="submit"]')
                ->visit('/courses/progress-course')
                ->waitForText('Get instant access', 10)
                ->press('Get instant access')
                ->waitForText('You are enrolled', 10)
                ->visit('/courses/progress-course/lessons/intro')
                ->waitForText('Completed', 10)
                ->assertSee('Completed')
                ->visit('/courses/progress-course')
                ->waitForText('Progress:', 5)
                ->assertSee('Progress: 100%');
        });
    }
}
