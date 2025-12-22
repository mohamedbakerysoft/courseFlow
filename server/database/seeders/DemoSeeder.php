<?php

namespace Database\Seeders;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Actions\Payments\ApproveManualPaymentAction;
use App\Actions\Progress\MarkLessonCompletedAction;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))->first();
        if (! $admin) {
            $admin = User::updateOrCreate(
                ['email' => config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL)],
                [
                    'name' => 'Omar Khaled',
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_ADMIN,
                    'profile_image_path' => 'images/demo/instructor-omar.jpg',
                    'bio' => 'Creator of CourseFlow and instructor for modern Laravel, Tailwind and course business workflows.',
                    'social_links' => [
                        'website' => 'https://example.com',
                        'twitter' => 'https://twitter.com/omar_codes',
                        'github' => 'https://github.com/example',
                        'youtube' => 'https://youtube.com/@omar-courseflow',
                    ],
                ]
            );
        } else {
            $admin->update([
                'name' => 'Omar Khaled',
                'profile_image_path' => $admin->profile_image_path ?: 'images/demo/instructor-omar.jpg',
                'bio' => $admin->bio ?: 'Creator of CourseFlow and instructor for modern Laravel, Tailwind and course business workflows.',
                'social_links' => $admin->social_links ?: [
                    'website' => 'https://example.com',
                    'twitter' => 'https://twitter.com/omar_codes',
                    'github' => 'https://github.com/example',
                    'youtube' => 'https://youtube.com/@omar-courseflow',
                ],
            ]);
        }

        $instructor = User::updateOrCreate(
            ['email' => 'instructor@demo.com'],
            [
                'name' => 'CourseFlow Instructor',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
                'profile_image_path' => 'images/demo/instructor-omar.jpg',
                'bio' => 'Hands-on instructor teaching how to launch, sell and scale your courses with CourseFlow.',
                'social_links' => [
                    'twitter' => 'https://twitter.com/courseflow_demo',
                    'linkedin' => 'https://linkedin.com/in/courseflow-demo',
                ],
            ]
        );

        $primaryStudent = User::updateOrCreate(
            ['email' => 'student@demo.com'],
            [
                'name' => 'Demo Student',
                'password' => bcrypt('password'),
                'role' => User::ROLE_STUDENT,
            ]
        );

        $studentProfiles = [
            ['name' => 'Sara Ahmed', 'email' => 'sara@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1544723795-3fb6469f70f2?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Mohamed Ali', 'email' => 'mohamed@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Lina Youssef', 'email' => 'lina@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1554151228-14d9def5b725?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Karim Hassan', 'email' => 'karim@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Nour El-Deen', 'email' => 'nour@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Layla Ibrahim', 'email' => 'layla@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1531123414780-fd9f06f3d0b3?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Hassan Omar', 'email' => 'hassan@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Amina Salah', 'email' => 'amina@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1544005311-94ddf0286df2?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Yousef Tarek', 'email' => 'yousef@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1511367461989-f85a21fda167?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Omar Farouk', 'email' => 'omar.farouk@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1545996124-0501ebae84d0?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Maya Nasser', 'email' => 'maya.nasser@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1546525848-3ce03ca516f6?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Ziad Mostafa', 'email' => 'ziad.mostafa@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1519340240031-4da6f06b74f5?q=80&w=640&auto=format&fit=crop'],
            ['name' => 'Rana Ismail', 'email' => 'rana.ismail@demo.com', 'avatar' => 'https://images.unsplash.com/photo-1520975916090-3105956dac38?q=80&w=640&auto=format&fit=crop'],
        ];

        $students = [$primaryStudent];
        foreach ($studentProfiles as $profile) {
            $students[] = User::updateOrCreate(
                ['email' => $profile['email']],
                [
                    'name' => $profile['name'],
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_STUDENT,
                    'profile_image_path' => $profile['avatar'] ?? null,
                ]
            );
        }

        $coursesData = [
            [
                'slug' => 'courseflow-mastery-launch',
                'title' => 'CourseFlow Mastery (Advanced • Best Seller)',
                'description' => 'Advanced program to launch a polished platform, set branding, and sell confidently with Stripe, PayPal and manual payments.',
                'is_free' => false,
                'price' => 129,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'laravel-fundamentals-online-courses',
                'title' => 'Laravel Fundamentals (Intermediate)',
                'description' => 'Intermediate track covering routing, Eloquent, Blade and actions to customize CourseFlow with confidence.',
                'is_free' => false,
                'price' => 49,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'tailwind-alpine-ui-kit',
                'title' => 'Tailwind & Alpine UI Kit (Intermediate)',
                'description' => 'Intermediate design kit for clean dashboards, lesson layouts and responsive landing pages with Tailwind & Alpine.',
                'is_free' => false,
                'price' => 39,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'course-launch-marketing-blueprint',
                'title' => 'Course Launch & Marketing (Advanced)',
                'description' => 'Advanced playbook to plan your launch, craft effective sales copy and set up funnels into CourseFlow checkout.',
                'is_free' => false,
                'price' => 89,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'courseflow-arabic-rtl',
                'title' => 'CourseFlow in Arabic (Intermediate): RTL & Localization',
                'description' => 'Intermediate guide to translate CourseFlow, enable RTL, and deliver a first-class Arabic experience.',
                'is_free' => false,
                'price' => 29,
                'language' => 'ar',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1504270997636-07ddfbd48945?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'courseflow-quickstart-mini-course',
                'title' => 'CourseFlow Quickstart (Beginner)',
                'description' => 'Beginner-friendly mini-course: follow a focused walkthrough from fresh install to a polished, demo-ready platform.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1513151233091-8a9bcbc2aba4?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'creator-productivity-systems',
                'title' => 'Creator Productivity Systems (Beginner)',
                'description' => 'Beginner track to plan lessons, batch content and keep your CourseFlow classroom organized.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1492724441997-5dc865305da7?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'web-accessibility-essentials',
                'title' => 'Web Accessibility Essentials (Intermediate)',
                'description' => 'Intermediate essentials: inclusive design, ARIA roles and practical accessibility audits for courses.',
                'is_free' => false,
                'price' => 59,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1520975916090-3105956dac38?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'video-editing-for-instructors',
                'title' => 'Video Editing for Instructors (Intermediate)',
                'description' => 'Intermediate workflows: trim, color correct and export high-quality lesson videos with simple steps.',
                'is_free' => false,
                'price' => 69,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1524253482453-3fed8d2fe12b?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'fitness-for-creators',
                'title' => 'Fitness for Creators',
                'description' => 'Simple routines to keep your energy high while recording and shipping lessons.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1518310958081-86aa83c67bb2?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_DRAFT,
            ],
            [
                'slug' => 'business-branding-foundations',
                'title' => 'Business Branding Foundations',
                'description' => 'Build a clear brand identity for your course business.',
                'is_free' => false,
                'price' => 79,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_DRAFT,
            ],
            [
                'slug' => 'designing-course-thumbnails',
                'title' => 'Designing Course Thumbnails',
                'description' => 'Create compelling 16:9 thumbnails that increase click-through rates.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
                'thumbnail_path' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1200&auto=format&fit=crop',
                'status' => Course::STATUS_DRAFT,
            ],
        ];

        $createdCourses = [];
        foreach ($coursesData as $i => $c) {
            $course = Course::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'title' => $c['title'],
                    'description' => $c['description'],
                    'thumbnail_path' => $c['thumbnail_path'] ?? 'images/demo/course-'.($i + 1).'.svg',
                    'price' => $c['price'],
                    'currency' => 'USD',
                    'is_free' => $c['is_free'],
                    'status' => $c['status'] ?? Course::STATUS_PUBLISHED,
                    'language' => $c['language'],
                    'instructor_id' => $admin->id,
                ]
            );

            $createdCourses[] = $course;

            $lessonsByCourse = match ($c['slug']) {
                'courseflow-mastery-launch' => [
                    [
                        'slug' => 'welcome-and-tour',
                        'title' => 'Welcome & Tour of CourseFlow',
                        'description' => 'See the student dashboard, public landing page and course details screens in action.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'install-with-sail',
                        'title' => 'Installing CourseFlow with Laravel Sail',
                        'description' => 'Spin up a local environment using Sail, run migrations and seed realistic demo data.',
                        'video_url' => 'https://www.youtube.com/embed/MFh0Fd7BsjE',
                    ],
                    [
                        'slug' => 'branding-and-settings',
                        'title' => 'Branding, Colors & Core Settings',
                        'description' => 'Update app name, colors and landing page copy so the platform looks like your brand.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'create-first-course',
                        'title' => 'Creating Your First Course',
                        'description' => 'Add a flagship course with a thumbnail, marketing copy and pricing options.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'add-lessons-and-video',
                        'title' => 'Adding Lessons, Videos & Resources',
                        'description' => 'Structure modules, paste video URLs and attach resources for students.',
                        'video_url' => 'https://www.youtube.com/embed/r5iWCtfltso',
                    ],
                    [
                        'slug' => 'payments-and-checkout',
                        'title' => 'Stripe, PayPal & Manual Payments',
                        'description' => 'Connect payment providers and walk through the full checkout experience.',
                        'video_url' => 'https://www.youtube.com/embed/7WFXl4-aCxs',
                    ],
                    [
                        'slug' => 'launch-and-iterate',
                        'title' => 'Launch, Iterate & Improve',
                        'description' => 'Collect feedback, improve lessons and ship updates without breaking existing students.',
                        'video_url' => 'https://www.youtube.com/embed/JJSoEo8JSnc',
                    ],
                ],
                'laravel-fundamentals-online-courses' => [
                    [
                        'slug' => 'laravel-basics-overview',
                        'title' => 'Laravel Basics for Course Platforms',
                        'description' => 'Understand how routes, controllers and actions power CourseFlow.',
                        'video_url' => 'https://www.youtube.com/embed/MFh0Fd7BsjE',
                    ],
                    [
                        'slug' => 'eloquent-and-relations',
                        'title' => 'Eloquent Models & Relationships',
                        'description' => 'See how users, courses, lessons and payments are related.',
                        'video_url' => 'https://www.youtube.com/embed/MFh0Fd7BsjE',
                    ],
                    [
                        'slug' => 'blade-and-components',
                        'title' => 'Blade Views & Reusable Components',
                        'description' => 'Customize course cards, layouts and public pages cleanly.',
                        'video_url' => 'https://www.youtube.com/embed/MFh0Fd7BsjE',
                    ],
                    [
                        'slug' => 'testing-and-dusk',
                        'title' => 'Feature Tests & Browser Tests',
                        'description' => 'Use Pest and Laravel Dusk to keep your demo stable.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'actions-and-services',
                        'title' => 'Actions & Services Architecture',
                        'description' => 'Extract business logic into actions that are easy to test.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                ],
                'tailwind-alpine-ui-kit' => [
                    [
                        'slug' => 'tailwind-setup',
                        'title' => 'Tailwind Setup & Design Tokens',
                        'description' => 'Configure colors, spacing and typography that match your brand.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'public-landing-layout',
                        'title' => 'Designing the Public Landing Page',
                        'description' => 'Build a hero, trust strip and featured courses grid.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'course-card-design',
                        'title' => 'Premium Course Card Design',
                        'description' => 'Create consistent 16:9 thumbnails and hover effects.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'alpine-interactions',
                        'title' => 'Alpine.js for Simple Interactions',
                        'description' => 'Add toggles, tabs and modals without heavy JavaScript.',
                        'video_url' => 'https://www.youtube.com/embed/r5iWCtfltso',
                    ],
                    [
                        'slug' => 'dark-mode-and-rtl',
                        'title' => 'Dark Mode & RTL Considerations',
                        'description' => 'Keep your UI readable in both light, dark and RTL layouts.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                ],
                'course-launch-marketing-blueprint' => [
                    [
                        'slug' => 'define-a-flagship',
                        'title' => 'Defining Your Flagship Course Offer',
                        'description' => 'Choose a clear transformation and promise for students.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'outline-and-curriculum',
                        'title' => 'Outlining Your Curriculum',
                        'description' => 'Turn your expertise into a structured, bingeable course.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'sales-page-copy',
                        'title' => 'Writing High-Converting Sales Page Copy',
                        'description' => 'Craft headlines, benefits and FAQs tailored to CourseFlow.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'launch-email-sequence',
                        'title' => 'Launch Email Sequences',
                        'description' => 'Plan pre-launch, launch and post-launch emails that convert.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'evergreen-funnels',
                        'title' => 'Evergreen Funnels into CourseFlow',
                        'description' => 'Connect your funnel tools so new students land directly in CourseFlow.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                ],
                'courseflow-arabic-rtl' => [
                    [
                        'slug' => 'arabic-language-setup',
                        'title' => 'Enabling Arabic & RTL Support',
                        'description' => 'Configure localization files and RTL CSS classes.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'translate-landing-page',
                        'title' => 'Translating the Landing Page',
                        'description' => 'Localize headlines, features and CTAs into Arabic.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'rtl-course-layout',
                        'title' => 'Designing RTL Course Layouts',
                        'description' => 'Ensure grids, cards and navigation feel natural in RTL.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'test-arabic-experience',
                        'title' => 'Testing the Arabic Student Experience',
                        'description' => 'Use Dusk to visually confirm RTL rendering.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                ],
                'courseflow-quickstart-mini-course' => [
                    [
                        'slug' => 'quickstart-overview',
                        'title' => 'Quickstart Overview',
                        'description' => 'See exactly what you will ship in the next 60 minutes.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'clone-and-install',
                        'title' => 'Clone, Install & Configure',
                        'description' => 'Clone the project, install dependencies and run migrations.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'seed-demo-data',
                        'title' => 'Seed Demo Data & Verify UI',
                        'description' => 'Load realistic demo courses, lessons and students.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'first-payment-test',
                        'title' => 'Run Your First Test Payment',
                        'description' => 'Walk through a full checkout from landing page to dashboard.',
                        'video_url' => 'https://www.youtube.com/embed/7WFXl4-aCxs',
                    ],
                ],
                'creator-productivity-systems' => [
                    [
                        'slug' => 'plan-content',
                        'title' => 'Planning Your Content Pipeline',
                        'description' => 'Turn scattered ideas into a repeatable content plan.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'batch-recording',
                        'title' => 'Batch Recording Sessions',
                        'description' => 'Record multiple lessons in one focused block.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'upload-and-organize',
                        'title' => 'Upload, Organize & Publish',
                        'description' => 'Upload videos, set positions and publish lessons on schedule.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'track-progress',
                        'title' => 'Track Student Progress',
                        'description' => 'Use CourseFlow progress data to see where students get stuck.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'optimize-routine',
                        'title' => 'Optimize Your Weekly Routine',
                        'description' => 'Protect time to improve courses while staying consistent.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                ],
                'web-accessibility-essentials' => [
                    [
                        'slug' => 'why-accessibility',
                        'title' => 'Why Accessibility Matters',
                        'description' => 'A quick overview of inclusive design principles.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'aria-landmarks',
                        'title' => 'ARIA Landmarks',
                        'description' => 'Structure pages for assistive technologies.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'color-contrast',
                        'title' => 'Color Contrast Basics',
                        'description' => 'Ensure readable, accessible interfaces.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'keyboard-nav',
                        'title' => 'Keyboard Navigation',
                        'description' => 'Make your app usable without a mouse.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                ],
                'video-editing-for-instructors' => [
                    [
                        'slug' => 'editing-basics',
                        'title' => 'Editing Basics',
                        'description' => 'Cut and trim clips for clarity.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'audio-cleanup',
                        'title' => 'Audio Cleanup',
                        'description' => 'Remove noise and balance levels.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'color-correction',
                        'title' => 'Color Correction',
                        'description' => 'Improve visual consistency.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'export-settings',
                        'title' => 'Export Settings',
                        'description' => 'Render high-quality video files.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                ],
                'fitness-for-creators' => [
                    [
                        'slug' => 'morning-mobility',
                        'title' => 'Morning Mobility',
                        'description' => 'Quick routine to start the day.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'desk-stretching',
                        'title' => 'Desk Stretching',
                        'description' => 'Relieve tension during editing.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'recording-posture',
                        'title' => 'Recording Posture',
                        'description' => 'Stay comfortable while filming.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'energy-routine',
                        'title' => 'Energy Routine',
                        'description' => 'Boost energy before sessions.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                ],
                'business-branding-foundations' => [
                    [
                        'slug' => 'brand-basics',
                        'title' => 'Brand Basics',
                        'description' => 'Clarify positioning and tone.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'visual-identity',
                        'title' => 'Visual Identity',
                        'description' => 'Colors, fonts and imagery.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'brand-assets',
                        'title' => 'Brand Assets',
                        'description' => 'Create templates and guides.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'launch-brand',
                        'title' => 'Launch Your Brand',
                        'description' => 'Rollout across platforms.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                ],
                'designing-course-thumbnails' => [
                    [
                        'slug' => 'thumbnail-principles',
                        'title' => 'Thumbnail Principles',
                        'description' => 'Composition and hierarchy.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'color-psychology',
                        'title' => 'Color Psychology',
                        'description' => 'Choose impactful palettes.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'typography-choices',
                        'title' => 'Typography Choices',
                        'description' => 'Readable, bold text placement.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                    [
                        'slug' => 'export-templates',
                        'title' => 'Export Templates',
                        'description' => 'Batch-create thumbnails.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                        'status' => Lesson::STATUS_DRAFT,
                    ],
                ],
                default => [],
            };

            foreach ($lessonsByCourse as $position => $lessonData) {
                Lesson::updateOrCreate(
                    ['course_id' => $course->id, 'slug' => $lessonData['slug']],
                    [
                        'title' => $lessonData['title'],
                        'description' => $lessonData['description'],
                        'video_url' => $lessonData['video_url'] ?? 'https://www.youtube.com/embed/dFgzHOX84xQ',
                        'position' => $position + 1,
                        'status' => $lessonData['status'] ?? Lesson::STATUS_PUBLISHED,
                    ]
                );
            }
        }

        $enroll = new EnrollUserInCourseAction;
        $markLessonCompleted = new MarkLessonCompletedAction;

        foreach ($students as $index => $student) {
            if ($index === 0) {
                $completedCourse = collect($createdCourses)->firstWhere('slug', 'courseflow-mastery-launch');
                $midProgressCourse = collect($createdCourses)->firstWhere('slug', 'laravel-fundamentals-online-courses');
                $secondaryMidProgressCourse = collect($createdCourses)->firstWhere('slug', 'courseflow-quickstart-mini-course');
                $notStartedCourse = collect($createdCourses)->firstWhere('slug', 'course-launch-marketing-blueprint');

                $selectedCourses = collect([
                    $completedCourse,
                    $midProgressCourse,
                    $secondaryMidProgressCourse,
                    $notStartedCourse,
                ])->filter();

                foreach ($selectedCourses as $course) {
                    $enroll->execute($student, $course);

                    $lessons = $course->lessons()->orderBy('position')->get();
                    if ($lessons->isEmpty()) {
                        continue;
                    }

                    $progressRatio = match ($course->slug) {
                        'courseflow-mastery-launch' => 1.0,
                        'laravel-fundamentals-online-courses' => 0.6,
                        'courseflow-quickstart-mini-course' => 0.4,
                        'course-launch-marketing-blueprint' => 0.0,
                        default => 0.0,
                    };

                    if ($progressRatio <= 0) {
                        continue;
                    }

                    $targetCount = max(1, (int) floor($lessons->count() * $progressRatio));
                    foreach ($lessons->take($targetCount) as $lesson) {
                        $markLessonCompleted->execute($student, $lesson);
                    }
                }

                continue;
            }

            $courseCount = match (true) {
                $index <= 3 => 3,
                $index <= 6 => 2,
                default => 1,
            };

            $selectedCourses = collect($createdCourses)->shuffle()->take($courseCount);

            foreach ($selectedCourses as $course) {
                $enroll->execute($student, $course);

                $lessons = $course->lessons()->orderBy('position')->get();
                if ($lessons->isEmpty()) {
                    continue;
                }

                $progressRatio = match (true) {
                    $index === 1 => 0.8,
                    $index === 2 => 0.6,
                    $index === 3 => 0.4,
                    default => (($index + $course->id) % 2 === 0) ? 0.3 : 0.7,
                };

                if ($progressRatio <= 0) {
                    continue;
                }

                $targetCount = max(1, (int) floor($lessons->count() * $progressRatio));
                foreach ($lessons->take($targetCount) as $lesson) {
                    $markLessonCompleted->execute($student, $lesson);
                }
            }
        }

        $primaryCourse = $createdCourses[0] ?? null;
        $secondaryCourse = $createdCourses[2] ?? null;
        $rtlCourse = $createdCourses[4] ?? null;
        $brandingDraftCourse = collect($createdCourses)->firstWhere('slug', 'business-branding-foundations');

        if ($primaryCourse) {
            Payment::updateOrCreate(
                ['external_reference' => 'demo-stripe-paid-1'],
                [
                    'user_id' => $primaryStudent->id,
                    'course_id' => $primaryCourse->id,
                    'provider' => 'stripe',
                    'amount' => $primaryCourse->price,
                    'currency' => 'USD',
                    'status' => Payment::STATUS_PAID,
                    'stripe_session_id' => 'demo_stripe_session_1',
                ]
            );
        }

        if ($secondaryCourse && isset($students[1])) {
            Payment::updateOrCreate(
                ['external_reference' => 'demo-paypal-paid-1'],
                [
                    'user_id' => $students[1]->id,
                    'course_id' => $secondaryCourse->id,
                    'provider' => 'paypal',
                    'amount' => $secondaryCourse->price,
                    'currency' => 'USD',
                    'status' => Payment::STATUS_PAID,
                ]
            );
        }

        if ($rtlCourse && isset($students[2])) {
            Payment::updateOrCreate(
                ['external_reference' => 'demo-manual-pending-1'],
                [
                    'user_id' => $students[2]->id,
                    'course_id' => $rtlCourse->id,
                    'provider' => 'manual',
                    'amount' => $rtlCourse->price,
                    'currency' => 'USD',
                    'status' => Payment::STATUS_PENDING,
                    'proof_path' => 'storage/manual-payments/demo-proof.jpg',
                ]
            );
        }

        if ($brandingDraftCourse && isset($students[3])) {
            $pending = Payment::updateOrCreate(
                ['external_reference' => 'demo-manual-approved-1'],
                [
                    'user_id' => $students[3]->id,
                    'course_id' => $brandingDraftCourse->id,
                    'provider' => 'manual',
                    'amount' => $brandingDraftCourse->price,
                    'currency' => 'USD',
                    'status' => Payment::STATUS_PENDING,
                    'proof_path' => 'storage/manual-payments/demo-proof-2.jpg',
                ]
            );
            $approver = $admin;
            (new ApproveManualPaymentAction(new EnrollUserInCourseAction))->execute($pending, $approver);
        }

        if ($secondaryCourse && isset($students[4])) {
            Payment::updateOrCreate(
                ['external_reference' => 'demo-stripe-failed-1'],
                [
                    'user_id' => $students[4]->id,
                    'course_id' => $secondaryCourse->id,
                    'provider' => 'stripe',
                    'amount' => $secondaryCourse->price,
                    'currency' => 'USD',
                    'status' => Payment::STATUS_FAILED,
                    'stripe_session_id' => 'demo_stripe_session_failed_1',
                ]
            );
        }
    }
}
