<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        $this->call(PageSeeder::class);
        if (config('demo.enabled') && app()->environment(['local', 'demo', 'dusk'])) {
            $this->call(DemoSeeder::class);
        }

        $sampleCourse = Course::updateOrCreate(
            ['slug' => 'sample-course'],
            [
                'title' => 'Course Business Quickstart',
                'thumbnail_path' => 'images/demo/real/course-real-1.jpg',
                'description' => 'A practical starter course for instructors who want to package expertise, present it clearly, and start selling with confidence.',
                'price' => 0,
                'currency' => 'USD',
                'is_free' => true,
                'status' => Course::STATUS_PUBLISHED,
                'language' => 'en',
                'instructor_id' => User::query()->where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))->first()?->id,
            ],
        );

        $sampleLessons = [
            [
                'slug' => 'define-your-course-offer',
                'title' => 'Define Your Course Offer',
                'description' => 'Clarify who the course is for, the promise it makes, and how to position it.',
            ],
            [
                'slug' => 'shape-the-student-journey',
                'title' => 'Shape the Student Journey',
                'description' => 'Plan the lesson order so students know exactly where to start and what comes next.',
            ],
            [
                'slug' => 'write-clear-sales-copy',
                'title' => 'Write Clear Sales Copy',
                'description' => 'Turn your course value into a clean headline, benefit-led description, and strong CTA.',
            ],
            [
                'slug' => 'publish-and-enroll',
                'title' => 'Publish and Enroll',
                'description' => 'Review pricing, enrollment flow, and launch the course with a smoother buying experience.',
            ],
        ];

        foreach ($sampleLessons as $position => $lesson) {
            Lesson::updateOrCreate(
                ['course_id' => $sampleCourse->id, 'slug' => $lesson['slug']],
                [
                    'title' => $lesson['title'],
                    'description' => $lesson['description'],
                    'video_url' => 'https://player.vimeo.com/video/76979871',
                    'position' => $position + 1,
                    'status' => Lesson::STATUS_PUBLISHED,
                ],
            );
        }

        User::query()
            ->where('role', User::ROLE_ADMIN)
            ->where(function ($query) {
                $query->whereNull('bio')->orWhere('bio', '')->orWhere('bio', 'Instructor bio');
            })
            ->update([
                'name' => 'Nour Khaled',
                'bio' => 'Founder of CourseFlow, helping independent instructors build polished course businesses with stronger branding, clearer checkout flows, and a more confident learning experience.',
            ]);

        User::query()
            ->where('role', User::ROLE_ADMIN)
            ->where(function ($query) {
                $query->whereNull('profile_image_path')->orWhere('profile_image_path', '')->orWhere('profile_image_path', 'images/demo/instructor-omar.jpg')->orWhere('profile_image_path', 'images/demo/instructor-owner.jpg');
            })
            ->update([
                'profile_image_path' => 'images/demo/real/hero-formal-2.jpg',
            ]);

        Setting::updateOrCreate(['key' => 'landing.show_contact_form'], ['value' => true]);
        Setting::updateOrCreate(['key' => 'typography.english_font'], ['value' => 'Poppins']);
        Setting::updateOrCreate(['key' => 'instructor.social.youtube'], ['value' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE']);
        Setting::updateOrCreate(['key' => 'hero.image'], ['value' => 'images/demo/real/hero-formal-2.jpg']);
        Setting::updateOrCreate(['key' => 'hero.image_path'], ['value' => 'images/demo/real/hero-formal-2.jpg']);
        Setting::updateOrCreate(['key' => 'hero.image_fit'], ['value' => 'cover']);
        Setting::updateOrCreate(['key' => 'hero.image_ratio'], ['value' => '4:5']);
        Setting::updateOrCreate(['key' => 'hero.image_focus'], ['value' => 'center']);
    }
}
