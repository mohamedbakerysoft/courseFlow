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
        Setting::updateOrCreate(['key' => 'landing.show_platform_proof'], ['value' => true]);
        Setting::updateOrCreate(['key' => 'landing.show_problem_section'], ['value' => true]);
        Setting::updateOrCreate(['key' => 'landing.show_flow_section'], ['value' => true]);
        Setting::updateOrCreate(['key' => 'landing.show_faq_section'], ['value' => true]);
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
        Setting::updateOrCreate(['key' => 'landing.testimonials.0.name'], ['value' => 'Maya Collins']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.0.role'], ['value' => 'Course buyer']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.0.quote'], ['value' => 'The checkout felt clear, the lessons opened instantly, and the platform looked polished from the first click.']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.0.avatar'], ['value' => 'images/demo/avatar-1.svg']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.1.name'], ['value' => 'Ethan Brooks']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.1.role'], ['value' => 'Business coach']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.1.quote'], ['value' => 'Learnova made the course offer feel trustworthy before I even reached the pricing section.']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.1.avatar'], ['value' => 'images/demo/avatar-2.svg']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.2.name'], ['value' => 'Sofia Turner']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.2.role'], ['value' => 'Consultant']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.2.quote'], ['value' => 'I liked how easy it was to move from browsing to enrollment without any confusing extra steps.']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.2.avatar'], ['value' => 'images/demo/avatar-3.svg']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.3.name'], ['value' => 'Daniel Ross']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.3.role'], ['value' => 'Online educator']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.3.quote'], ['value' => 'The lesson flow feels structured, premium, and professional enough to trust with a paid program.']);
        Setting::updateOrCreate(['key' => 'landing.testimonials.3.avatar'], ['value' => 'images/demo/avatar-4.svg']);
        Setting::updateOrCreate(['key' => 'landing.faqs.0.question'], ['value' => 'Can I offer both free and paid courses?']);
        Setting::updateOrCreate(['key' => 'landing.faqs.0.answer'], ['value' => 'Yes. You can publish free offers, paid programs, and downloadable books while keeping the same storefront experience.']);
        Setting::updateOrCreate(['key' => 'landing.faqs.1.question'], ['value' => 'What happens after a student enrolls?']);
        Setting::updateOrCreate(['key' => 'landing.faqs.1.answer'], ['value' => 'Students get instant access to their purchased course or resource, with lesson order and progress tracking ready right away.']);
        Setting::updateOrCreate(['key' => 'landing.faqs.2.question'], ['value' => 'Can I manage landing page content from the admin panel?']);
        Setting::updateOrCreate(['key' => 'landing.faqs.2.answer'], ['value' => 'Yes. Hero copy, testimonials, FAQs, visibility toggles, contact block, and legal content are all controlled from admin settings.']);
        Setting::updateOrCreate(['key' => 'landing.faqs.3.question'], ['value' => 'Which payment methods can I enable?']);
        Setting::updateOrCreate(['key' => 'landing.faqs.3.answer'], ['value' => 'Depending on your setup, you can enable Stripe, PayPal, and manual payment instructions for one-time purchases.']);
        Setting::updateOrCreate(['key' => 'landing.faqs.4.question'], ['value' => 'Can learners resume where they stopped?']);
        Setting::updateOrCreate(['key' => 'landing.faqs.4.answer'], ['value' => 'Yes. Lesson progress remains visible so learners can return to the next step without losing their place.']);
        Setting::updateOrCreate(['key' => 'legal.terms.en'], ['value' => "1. Introduction\nLearnova provides digital courses and educational resources for individual learners and customers.\n\n2. Accounts and access\nYou are responsible for the security of your account and any activity that happens under it.\n\n3. Purchases and delivery\nPaid products are delivered after successful payment confirmation. Free products become available immediately after enrollment when applicable.\n\n4. Personal use\nCourse videos, books, downloads, and supporting materials are licensed for personal use only and may not be resold, copied, or redistributed without permission.\n\n5. Refunds\nRefund terms may vary by offer and are described on the relevant sales page or purchase flow.\n\n6. Contact\nIf you need help with billing, access, or content questions, use the contact form available on the storefront."]);
        Setting::updateOrCreate(['key' => 'legal.privacy.en'], ['value' => "1. Information we collect\nLearnova may collect account details, purchase information, communication history, and basic usage data required to operate the platform.\n\n2. How we use information\nData is used to deliver purchased content, improve the learning experience, provide support, and keep the platform secure.\n\n3. Payments and providers\nPayments may be processed by third-party providers such as Stripe or PayPal. Sensitive financial details are handled according to the policies of those providers.\n\n4. Cookies and analytics\nCookies may be used to remember preferences, support login sessions, and understand how visitors use the storefront.\n\n5. Your choices\nYou can request updates or removal of your data where applicable by contacting the site owner through the storefront contact form.\n\n6. Updates\nThis privacy policy may be updated to reflect operational or legal changes, and the latest version published on the site is the one in effect."]);

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
