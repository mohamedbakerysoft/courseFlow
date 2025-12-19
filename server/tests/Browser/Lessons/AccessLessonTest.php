<?php

namespace Tests\Browser\Lessons;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AccessLessonTest extends DuskTestCase
{
    public function test_open_lesson_after_enroll(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        $user = User::updateOrCreate(['email' => 'student2@example.com'], [
            'name' => 'Student 2',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_STUDENT,
        ]);

        $course = Course::updateOrCreate(['slug' => 'video-course'], [
            'title' => 'Video Course',
            'description' => 'Course with lesson',
            'price' => 0,
            'currency' => 'USD',
            'is_free' => true,
            'status' => \App\Models\Course::STATUS_PUBLISHED,
            'language' => 'en',
        ]);

        Lesson::updateOrCreate(['course_id' => $course->id, 'slug' => 'lesson-1'], [
            'title' => 'Lesson 1',
            'description' => 'First lesson',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'position' => 1,
            'status' => \App\Models\Lesson::STATUS_PUBLISHED,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/courses')
                ->clickLink('Video Course')
                ->assertPathIs('/courses/video-course')
                ->waitForText('Enroll', 10)
                ->press('Enroll')
                ->waitForText('You are enrolled', 10)
                ->visit('/courses/video-course/lessons/lesson-1')
                ->waitForLocation('/courses/video-course/lessons/lesson-1', 10)
                ->waitForText('Lesson 1', 10)
                ->assertSee('Lesson 1')
                ->assertPresent('iframe');
        });
    }
}
