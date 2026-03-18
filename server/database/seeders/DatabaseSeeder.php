<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

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
                'thumbnail_path' => 'images/demo/real/course-real-7.jpg',
                'description' => 'A practical starter course for instructors who want to package expertise, present it clearly, and start selling with confidence.',
                'price' => 0,
                'currency' => 'USD',
                'is_free' => true,
                'status' => Course::STATUS_PUBLISHED,
                'product_type' => Course::TYPE_COURSE,
                'language' => 'en',
                'instructor_id' => User::query()->where('email', 'instructor@demo.com')->first()?->id
                    ?: User::query()->where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))->first()?->id,
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
                'bio' => 'Founder of Learnova, helping independent instructors build polished course businesses with stronger branding, clearer checkout flows, and a more confident learning experience.',
            ]);

        User::query()
            ->where('role', User::ROLE_ADMIN)
            ->where(function ($query) {
                $query->whereNull('profile_image_path')->orWhere('profile_image_path', '')->orWhere('profile_image_path', 'images/demo/instructor-omar.jpg')->orWhere('profile_image_path', 'images/demo/instructor-owner.jpg');
            })
            ->update([
                'profile_image_path' => 'images/demo/real/hero-formal-2.jpg',
            ]);

        Storage::disk('public')->makeDirectory('books');
        $sampleBookPath = 'books/courseflow-sample-resource.pdf';
        if (! Storage::disk('public')->exists($sampleBookPath)) {
            Storage::disk('public')->put($sampleBookPath, "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Count 1 /Kids [3 0 R] >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n4 0 obj\n<< /Length 95 >>\nstream\nBT\n/F1 16 Tf\n72 760 Td\n(Learnova sample resource) Tj\n0 -30 Td\n/F1 12 Tf\n(Replace this seeded PDF from the admin Books area.) Tj\nET\nendstream\nendobj\n5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000241 00000 n \n0000000400 00000 n \ntrailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n470\n%%EOF");
        }

        Course::updateOrCreate(
            ['slug' => 'courseflow-sample-resource-book'],
            [
                'title' => 'Learnova Sample Resource Book',
                'thumbnail_path' => 'images/demo/real/course-real-2.jpg',
                'description' => 'A seeded downloadable resource that demonstrates free and paid digital book delivery inside Learnova.',
                'download_file_path' => $sampleBookPath,
                'price' => 0,
                'currency' => 'USD',
                'is_free' => true,
                'status' => Course::STATUS_PUBLISHED,
                'product_type' => Course::TYPE_BOOK,
                'language' => 'en',
                'instructor_id' => User::query()->where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))->first()?->id,
            ],
        );

        Setting::updateOrCreate(['key' => 'landing.show_contact_form'], ['value' => true]);
        Setting::updateOrCreate(['key' => 'demo.enabled'], ['value' => true]);
        Setting::updateOrCreate(['key' => 'site.default_language'], ['value' => 'en']);
        Setting::updateOrCreate(['key' => 'ui.theme.default'], ['value' => 'light']);
        Setting::updateOrCreate(['key' => 'theme.primary'], ['value' => '#F5B800']);
        Setting::updateOrCreate(['key' => 'theme.secondary'], ['value' => '#0B0B0B']);
        Setting::updateOrCreate(['key' => 'theme.accent'], ['value' => '#F7F7F7']);
        Setting::updateOrCreate(['key' => 'theme.bg'], ['value' => '#FFFFFF']);
        Setting::updateOrCreate(['key' => 'theme.text'], ['value' => '#0B0B0B']);
        Setting::updateOrCreate(['key' => 'theme.text_muted'], ['value' => '#4A4A4A']);
        Setting::updateOrCreate(['key' => 'theme.primary_hover'], ['value' => '#D8A100']);
        Setting::updateOrCreate(['key' => 'theme.error'], ['value' => '#DC2626']);
        Setting::updateOrCreate(['key' => 'typography.english_font'], ['value' => 'Manrope']);
        Setting::updateOrCreate(['key' => 'instructor.social.youtube'], ['value' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE']);
        Setting::updateOrCreate(['key' => 'landing.hero_video_url'], ['value' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE']);
        Setting::updateOrCreate(['key' => 'hero.image'], ['value' => 'images/demo/real/hero-formal-2.jpg']);
        Setting::updateOrCreate(['key' => 'hero.image_path'], ['value' => 'images/demo/real/hero-formal-2.jpg']);
        Setting::updateOrCreate(['key' => 'hero.image_fit'], ['value' => 'cover']);
        Setting::updateOrCreate(['key' => 'hero.image_ratio'], ['value' => '4:5']);
        Setting::updateOrCreate(['key' => 'hero.image_focus'], ['value' => 'center']);

        Setting::query()->whereIn('key', [
            'typography.arabic_font',
            'landing.hero_title_ar',
            'landing.hero_subtitle_ar',
            'hero.title.ar',
            'hero.subtitle.ar',
            'legal.terms.ar',
            'legal.privacy.ar',
        ])->delete();
    }
}
