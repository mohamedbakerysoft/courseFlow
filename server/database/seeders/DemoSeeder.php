<?php

namespace Database\Seeders;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Actions\Payments\ApproveManualPaymentAction;
use App\Actions\Progress\MarkLessonCompletedAction;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $defaultYouTubeUrl = 'https://www.youtube.com/watch?v=M7lc1UVf-VE';
        $demoBookFiles = $this->ensureDemoBookFiles();

        $demoCourseCovers = [
            'images/demo/real/course-real-1.jpg',
            'images/demo/real/course-real-2.jpg',
            'images/demo/real/course-real-3.jpg',
            'images/demo/real/course-real-4.jpg',
            'images/demo/real/course-real-5.jpg',
            'images/demo/real/course-real-6.jpg',
            'images/demo/real/course-real-7.jpg',
        ];

        $demoAvatars = collect(range(1, 4))
            ->map(fn (int $index) => 'images/demo/avatar-'.$index.'.svg')
            ->all();

        $admin = User::where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))->first();
        if (! $admin) {
            $admin = User::updateOrCreate(
                ['email' => config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL)],
                [
                    'name' => 'Nour Khaled',
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_ADMIN,
                    'profile_image_path' => 'images/demo/real/hero-formal-2.jpg',
                    'bio' => 'Founder of Learnova and instructor focused on helping creators launch polished course businesses with stronger branding, trusted checkout, and premium student experiences.',
                    'social_links' => [
                        'website' => 'https://example.com',
                        'twitter' => 'https://twitter.com/learnova',
                        'linkedin' => 'https://linkedin.com/in/learnova',
                        'youtube' => $defaultYouTubeUrl,
                    ],
                ]
            );
        } else {
            $admin->update([
                'name' => 'Nour Khaled',
                'profile_image_path' => (! $admin->profile_image_path || str_contains((string) $admin->profile_image_path, 'instructor-omar.jpg') || str_contains((string) $admin->profile_image_path, 'instructor-owner.jpg')) ? 'images/demo/real/hero-formal-2.jpg' : $admin->profile_image_path,
                'bio' => (! $admin->bio || $admin->bio === 'Instructor bio' || str_contains((string) $admin->bio, 'CourseFlow')) ? 'Founder of Learnova and instructor focused on helping creators launch polished course businesses with stronger branding, trusted checkout, and premium student experiences.' : $admin->bio,
                'social_links' => $admin->social_links ?: [
                    'website' => 'https://example.com',
                    'twitter' => 'https://twitter.com/learnova',
                    'linkedin' => 'https://linkedin.com/in/learnova',
                    'youtube' => $defaultYouTubeUrl,
                ],
            ]);
        }

        $instructor = User::updateOrCreate(
            ['email' => 'instructor@demo.com'],
            [
                'name' => 'Maya Hassan',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
                'profile_image_path' => 'images/demo/real/hero-formal-1.jpg',
                'bio' => 'Hands-on instructor teaching how to position, launch, and sell professional online courses with Learnova.',
                'social_links' => [
                    'twitter' => 'https://twitter.com/learnova_demo',
                    'linkedin' => 'https://linkedin.com/in/learnova-demo',
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
            ['name' => 'Sara Ahmed', 'email' => 'sara@demo.com'],
            ['name' => 'Mohamed Ali', 'email' => 'mohamed@demo.com'],
            ['name' => 'Lina Youssef', 'email' => 'lina@demo.com'],
            ['name' => 'Karim Hassan', 'email' => 'karim@demo.com'],
            ['name' => 'Nour El-Deen', 'email' => 'nour@demo.com'],
            ['name' => 'Layla Ibrahim', 'email' => 'layla@demo.com'],
            ['name' => 'Hassan Omar', 'email' => 'hassan@demo.com'],
            ['name' => 'Amina Salah', 'email' => 'amina@demo.com'],
            ['name' => 'Yousef Tarek', 'email' => 'yousef@demo.com'],
            ['name' => 'Omar Farouk', 'email' => 'omar.farouk@demo.com'],
            ['name' => 'Maya Nasser', 'email' => 'maya.nasser@demo.com'],
            ['name' => 'Ziad Mostafa', 'email' => 'ziad.mostafa@demo.com'],
            ['name' => 'Rana Ismail', 'email' => 'rana.ismail@demo.com'],
        ];

        $students = [$primaryStudent];
        foreach ($studentProfiles as $index => $profile) {
            $students[] = User::updateOrCreate(
                ['email' => $profile['email']],
                [
                    'name' => $profile['name'],
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_STUDENT,
                    'profile_image_path' => $demoAvatars[$index % count($demoAvatars)],
                ]
            );
        }

        $coursesData = [
            [
                'slug' => 'courseflow-mastery-launch',
                'title' => 'Learnova Mastery (Advanced • Best Seller)',
                'description' => 'Advanced program to launch a polished platform, set branding, and sell confidently with Stripe, PayPal and manual payments.',
                'is_free' => false,
                'price' => 129,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[0],
                'instructor_email' => $admin->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'laravel-fundamentals-online-courses',
                'title' => 'Laravel Fundamentals (Intermediate)',
                'description' => 'Intermediate track covering routing, Eloquent, Blade and actions to customize Learnova with confidence.',
                'is_free' => false,
                'price' => 49,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[5],
                'instructor_email' => $admin->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'tailwind-alpine-ui-kit',
                'title' => 'Tailwind & Alpine UI Kit (Intermediate)',
                'description' => 'Intermediate design kit for clean dashboards, lesson layouts and responsive landing pages with Tailwind & Alpine.',
                'is_free' => false,
                'price' => 39,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[3],
                'instructor_email' => $instructor->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'course-launch-marketing-blueprint',
                'title' => 'Course Launch & Marketing (Advanced)',
                'description' => 'Advanced playbook to plan your launch, craft effective sales copy and set up funnels into Learnova checkout.',
                'is_free' => false,
                'price' => 89,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[3],
                'instructor_email' => $admin->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'courseflow-content-operations',
                'title' => 'Course Content Operations (Intermediate)',
                'description' => 'Intermediate system for refining lesson structure, polishing copy, and keeping the student journey clear from first click to completion.',
                'is_free' => false,
                'price' => 29,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[4],
                'instructor_email' => $instructor->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'courseflow-quickstart-mini-course',
                'title' => 'Learnova Quickstart (Beginner)',
                'description' => 'Beginner-friendly mini-course: follow a focused walkthrough from fresh install to a polished, demo-ready platform.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[6],
                'instructor_email' => $admin->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'creator-productivity-systems',
                'title' => 'Creator Productivity Systems (Beginner)',
                'description' => 'Beginner track to plan lessons, batch content and keep your Learnova classroom organized.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[1],
                'instructor_email' => $instructor->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'web-accessibility-essentials',
                'title' => 'Web Accessibility Essentials (Intermediate)',
                'description' => 'Intermediate essentials: inclusive design, ARIA roles and practical accessibility audits for courses.',
                'is_free' => false,
                'price' => 59,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[6],
                'instructor_email' => $admin->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'video-editing-for-instructors',
                'title' => 'Video Editing for Instructors (Intermediate)',
                'description' => 'Intermediate workflows: trim, color correct and export high-quality lesson videos with simple steps.',
                'is_free' => false,
                'price' => 69,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[2],
                'instructor_email' => $admin->email,
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'fitness-for-creators',
                'title' => 'Fitness for Creators',
                'description' => 'Simple routines to keep your energy high while recording and shipping lessons.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[2],
                'status' => Course::STATUS_DRAFT,
            ],
            [
                'slug' => 'business-branding-foundations',
                'title' => 'Business Branding Foundations',
                'description' => 'Build a clear brand identity for your course business.',
                'is_free' => false,
                'price' => 79,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[3],
                'status' => Course::STATUS_DRAFT,
            ],
            [
                'slug' => 'designing-course-thumbnails',
                'title' => 'Designing Course Thumbnails',
                'description' => 'Create compelling 16:9 thumbnails that increase click-through rates.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
                'thumbnail_path' => $demoCourseCovers[4],
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
                    'product_type' => Course::TYPE_COURSE,
                    'price' => $c['price'],
                    'currency' => 'USD',
                    'is_free' => $c['is_free'],
                    'status' => $c['status'] ?? Course::STATUS_PUBLISHED,
                    'language' => $c['language'],
                    'instructor_id' => ($c['instructor_email'] ?? null) === $instructor->email ? $instructor->id : $admin->id,
                ]
            );

            $createdCourses[] = $course;

            $lessonsByCourse = match ($c['slug']) {
                'courseflow-mastery-launch' => [
                    [
                        'slug' => 'welcome-and-tour',
                        'title' => 'Welcome & Tour of Learnova',
                        'description' => 'See the student dashboard, public landing page and course details screens in action.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                    [
                        'slug' => 'install-with-sail',
                        'title' => 'Installing Learnova with Laravel Sail',
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
                        'description' => 'Understand how routes, controllers and actions power Learnova.',
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
                        'slug' => 'dark-mode-consistency',
                        'title' => 'Dark Mode Consistency',
                        'description' => 'Keep your UI readable and balanced in both light and dark themes.',
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
                        'description' => 'Craft headlines, benefits and FAQs tailored to Learnova.',
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
                        'title' => 'Evergreen Funnels into Learnova',
                        'description' => 'Connect your funnel tools so new students land directly in Learnova.',
                        'video_url' => 'https://www.youtube.com/embed/MYyJ4PuL4pY',
                    ],
                ],
                'courseflow-content-operations' => [
                    [
                        'slug' => 'content-operations-setup',
                        'title' => 'Set Up Your Content Operations Workflow',
                        'description' => 'Create a simple publishing routine for course pages, lessons, and updates.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'refine-landing-copy',
                        'title' => 'Refine Your Landing Page Copy',
                        'description' => 'Improve headlines, proof, and CTA clarity without making the page feel busy.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'course-layout-systems',
                        'title' => 'Course Layout Systems',
                        'description' => 'Make sure cards, grids, and navigation stay clean and easy to scan.',
                        'video_url' => 'https://www.youtube.com/embed/dFgzHOX84xQ',
                    ],
                    [
                        'slug' => 'qa-student-experience',
                        'title' => 'Quality Check the Student Experience',
                        'description' => 'Review the lesson flow visually and confirm the journey feels complete.',
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
                        'description' => 'Use Learnova progress data to see where students get stuck.',
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

        $booksData = [
            [
                'slug' => 'courseflow-launch-playbook-book',
                'title' => 'Learnova Launch Playbook',
                'description' => 'A polished downloadable guide that helps solo instructors structure offers, plan launch assets, and improve checkout clarity before going live.',
                'thumbnail_path' => $demoCourseCovers[5],
                'download_file_path' => $demoBookFiles['playbook'],
                'price' => 19,
                'currency' => 'USD',
                'is_free' => false,
                'language' => 'en',
                'status' => Course::STATUS_PUBLISHED,
            ],
            [
                'slug' => 'courseflow-free-workbook',
                'title' => 'Learnova Free Workbook',
                'description' => 'A free workbook with planning prompts, launch checklists, and storefront review notes that visitors can download instantly.',
                'thumbnail_path' => $demoCourseCovers[6],
                'download_file_path' => $demoBookFiles['workbook'],
                'price' => 0,
                'currency' => 'USD',
                'is_free' => true,
                'language' => 'en',
                'status' => Course::STATUS_PUBLISHED,
            ],
        ];

        foreach ($booksData as $bookData) {
            Course::updateOrCreate(
                ['slug' => $bookData['slug']],
                array_merge($bookData, [
                    'product_type' => Course::TYPE_BOOK,
                    'instructor_id' => $admin->id,
                ])
            );
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
        $operationsCourse = $createdCourses[4] ?? null;
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

        if ($operationsCourse && isset($students[2])) {
            Payment::updateOrCreate(
                ['external_reference' => 'demo-manual-pending-1'],
                [
                    'user_id' => $students[2]->id,
                    'course_id' => $operationsCourse->id,
                    'provider' => 'manual',
                    'amount' => $operationsCourse->price,
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

        Setting::updateOrCreate(['key' => 'landing.show_contact_form'], ['value' => true]);
        Setting::updateOrCreate(['key' => 'ui.theme.default'], ['value' => 'light']);
        Setting::updateOrCreate(['key' => 'theme.primary'], ['value' => '#F5B800']);
        Setting::updateOrCreate(['key' => 'theme.secondary'], ['value' => '#0B0B0B']);
        Setting::updateOrCreate(['key' => 'theme.accent'], ['value' => '#F7F7F7']);
        Setting::updateOrCreate(['key' => 'theme.bg'], ['value' => '#FFFFFF']);
        Setting::updateOrCreate(['key' => 'theme.text'], ['value' => '#0B0B0B']);
        Setting::updateOrCreate(['key' => 'theme.text_muted'], ['value' => '#4A4A4A']);
        Setting::updateOrCreate(['key' => 'theme.primary_hover'], ['value' => '#D8A100']);
        Setting::updateOrCreate(['key' => 'theme.error'], ['value' => '#DC2626']);
        Setting::updateOrCreate(['key' => 'typography.english_font'], ['value' => 'Poppins']);
        Setting::updateOrCreate(['key' => 'demo.enabled'], ['value' => true]);
        Setting::updateOrCreate(['key' => 'instructor.social.youtube'], ['value' => $defaultYouTubeUrl]);
        Setting::updateOrCreate(['key' => 'landing.hero_video_url'], ['value' => $defaultYouTubeUrl]);
        Setting::updateOrCreate(['key' => 'hero.title.en'], ['value' => 'Launch courses with a storefront learners trust']);
        Setting::updateOrCreate(['key' => 'hero.subtitle.en'], ['value' => 'Sell digital courses with secure checkout, instant access, and structured lessons inside one clean experience.']);
        Setting::updateOrCreate(['key' => 'landing.feature_1_title'], ['value' => 'Secure checkout']);
        Setting::updateOrCreate(['key' => 'landing.feature_1_description'], ['value' => 'Offer card, PayPal, or manual payments without confusing the learner.']);
        Setting::updateOrCreate(['key' => 'landing.feature_2_title'], ['value' => 'Structured delivery']);
        Setting::updateOrCreate(['key' => 'landing.feature_2_description'], ['value' => 'Guide students through lessons with protected access and saved progress.']);
        Setting::updateOrCreate(['key' => 'landing.feature_3_title'], ['value' => 'Stronger instructor trust']);
        Setting::updateOrCreate(['key' => 'landing.feature_3_description'], ['value' => 'Show a real instructor, clear course cards, and a buying flow that feels trustworthy.']);
    }

    private function ensureDemoBookFiles(): array
    {
        Storage::disk('public')->makeDirectory('books');

        $playbookPath = 'books/courseflow-launch-playbook.pdf';
        $workbookPath = 'books/courseflow-free-workbook.pdf';

        $pdf = <<<'PDF'
%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Count 1 /Kids [3 0 R] >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>
endobj
4 0 obj
<< /Length 132 >>
stream
BT
/F1 20 Tf
72 760 Td
(Learnova Demo Download) Tj
0 -34 Td
/F1 12 Tf
(This is a seeded downloadable resource for demo and testing purposes.) Tj
0 -24 Td
(Replace it from the admin Books section with your real ebook or workbook.) Tj
ET
endstream
endobj
5 0 obj
<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>
endobj
xref
0 6
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
0000000241 00000 n 
0000000424 00000 n 
trailer
<< /Size 6 /Root 1 0 R >>
startxref
494
%%EOF
PDF;

        if (! Storage::disk('public')->exists($playbookPath)) {
            Storage::disk('public')->put($playbookPath, $pdf);
        }

        if (! Storage::disk('public')->exists($workbookPath)) {
            Storage::disk('public')->put($workbookPath, $pdf);
        }

        return [
            'playbook' => $playbookPath,
            'workbook' => $workbookPath,
        ];
    }
}
