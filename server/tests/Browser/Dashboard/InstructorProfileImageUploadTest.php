<?php

namespace Tests\Browser\Dashboard;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InstructorProfileImageUploadTest extends DuskTestCase
{
    public function test_upload_profile_image_and_see_on_course_page(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        $admin = User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_ADMIN,
        ]);

        Course::updateOrCreate(['slug' => 'upload-course'], [
            'title' => 'Upload Course',
            'description' => 'Desc',
            'price' => 0,
            'currency' => 'USD',
            'is_free' => true,
            'status' => \App\Models\Course::STATUS_PUBLISHED,
            'language' => 'en',
            'instructor_id' => $admin->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/dashboard/instructor/profile')
                ->attach('instructor_image', '/var/www/html/public/images/demo/Subject.png')
                ->press('Save Profile')
                ->assertPresent('img.rounded-full.object-cover')
                ->visit('/courses/upload-course')
                ->assertSee('Instructor')
                ->assertSee('Admin');
        });
    }
}
