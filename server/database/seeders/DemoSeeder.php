<?php

namespace Database\Seeders;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Actions\Progress\MarkLessonCompletedAction;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

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
            ['name' => 'Sara Ahmed', 'email' => 'sara@demo.com'],
            ['name' => 'Mohamed Ali', 'email' => 'mohamed@demo.com'],
            ['name' => 'Lina Youssef', 'email' => 'lina@demo.com'],
            ['name' => 'Karim Hassan', 'email' => 'karim@demo.com'],
            ['name' => 'Nour El-Deen', 'email' => 'nour@demo.com'],
            ['name' => 'Layla Ibrahim', 'email' => 'layla@demo.com'],
            ['name' => 'Hassan Omar', 'email' => 'hassan@demo.com'],
            ['name' => 'Amina Salah', 'email' => 'amina@demo.com'],
            ['name' => 'Yousef Tarek', 'email' => 'yousef@demo.com'],
        ];

        $students = [$primaryStudent];
        foreach ($studentProfiles as $profile) {
            $students[] = User::updateOrCreate(
                ['email' => $profile['email']],
                [
                    'name' => $profile['name'],
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_STUDENT,
                ]
            );
        }

        $coursesData = [
            [
                'slug' => 'courseflow-mastery-launch',
                'title' => 'CourseFlow Mastery: Launch Your Course Platform',
                'description' => 'Learn how to install CourseFlow with Sail, configure branding, publish courses and accept real payments with Stripe, PayPal and manual invoices.',
                'is_free' => false,
                'price' => 129,
                'language' => 'en',
            ],
            [
                'slug' => 'laravel-fundamentals-online-courses',
                'title' => 'Laravel Fundamentals for Online Course Creators',
                'description' => 'Understand routing, Eloquent, Blade and actions so you can confidently ship and customize Laravel-powered course platforms.',
                'is_free' => false,
                'price' => 49,
                'language' => 'en',
            ],
            [
                'slug' => 'tailwind-alpine-ui-kit',
                'title' => 'Tailwind & Alpine UI Kit for Creators',
                'description' => 'Design clean dashboards, lesson layouts and responsive landing pages using Tailwind CSS and Alpine.js.',
                'is_free' => false,
                'price' => 39,
                'language' => 'en',
            ],
            [
                'slug' => 'course-launch-marketing-blueprint',
                'title' => 'Course Launch & Marketing Blueprint',
                'description' => 'Plan your launch, craft sales copy and set up funnels that send students directly into your CourseFlow checkout.',
                'is_free' => false,
                'price' => 89,
                'language' => 'en',
            ],
            [
                'slug' => 'courseflow-arabic-rtl',
                'title' => 'CourseFlow in Arabic: RTL Layouts & Localization',
                'description' => 'Translate CourseFlow, enable RTL support and deliver a first-class Arabic learning experience.',
                'is_free' => false,
                'price' => 29,
                'language' => 'ar',
            ],
            [
                'slug' => 'courseflow-quickstart-mini-course',
                'title' => 'CourseFlow Quickstart: 60 Minute Mini-Course',
                'description' => 'Follow a focused, one-hour walkthrough that takes you from a fresh install to a polished, demo-ready platform.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
            ],
            [
                'slug' => 'creator-productivity-systems',
                'title' => 'Creator Productivity Systems for Instructors',
                'description' => 'Build a simple system to plan lessons, batch content and keep your CourseFlow classroom organized.',
                'is_free' => true,
                'price' => 0,
                'language' => 'en',
            ],
        ];

        $createdCourses = [];
        foreach ($coursesData as $i => $c) {
            $course = Course::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'title' => $c['title'],
                    'description' => $c['description'],
                    'thumbnail_path' => 'images/demo/course-'.($i + 1).'.svg',
                    'price' => $c['price'],
                    'currency' => 'USD',
                    'is_free' => $c['is_free'],
                    'status' => Course::STATUS_PUBLISHED,
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
                    ],
                    [
                        'slug' => 'install-with-sail',
                        'title' => 'Installing CourseFlow with Laravel Sail',
                        'description' => 'Spin up a local environment using Sail, run migrations and seed realistic demo data.',
                    ],
                    [
                        'slug' => 'branding-and-settings',
                        'title' => 'Branding, Colors & Core Settings',
                        'description' => 'Update app name, colors and landing page copy so the platform looks like your brand.',
                    ],
                    [
                        'slug' => 'create-first-course',
                        'title' => 'Creating Your First Course',
                        'description' => 'Add a flagship course with a thumbnail, marketing copy and pricing options.',
                    ],
                    [
                        'slug' => 'add-lessons-and-video',
                        'title' => 'Adding Lessons, Videos & Resources',
                        'description' => 'Structure modules, paste video URLs and attach resources for students.',
                    ],
                    [
                        'slug' => 'payments-and-checkout',
                        'title' => 'Stripe, PayPal & Manual Payments',
                        'description' => 'Connect payment providers and walk through the full checkout experience.',
                    ],
                    [
                        'slug' => 'launch-and-iterate',
                        'title' => 'Launch, Iterate & Improve',
                        'description' => 'Collect feedback, improve lessons and ship updates without breaking existing students.',
                    ],
                ],
                'laravel-fundamentals-online-courses' => [
                    [
                        'slug' => 'laravel-basics-overview',
                        'title' => 'Laravel Basics for Course Platforms',
                        'description' => 'Understand how routes, controllers and actions power CourseFlow.',
                    ],
                    [
                        'slug' => 'eloquent-and-relations',
                        'title' => 'Eloquent Models & Relationships',
                        'description' => 'See how users, courses, lessons and payments are related.',
                    ],
                    [
                        'slug' => 'blade-and-components',
                        'title' => 'Blade Views & Reusable Components',
                        'description' => 'Customize course cards, layouts and public pages cleanly.',
                    ],
                    [
                        'slug' => 'testing-and-dusk',
                        'title' => 'Feature Tests & Browser Tests',
                        'description' => 'Use Pest and Laravel Dusk to keep your demo stable.',
                    ],
                    [
                        'slug' => 'actions-and-services',
                        'title' => 'Actions & Services Architecture',
                        'description' => 'Extract business logic into actions that are easy to test.',
                    ],
                ],
                'tailwind-alpine-ui-kit' => [
                    [
                        'slug' => 'tailwind-setup',
                        'title' => 'Tailwind Setup & Design Tokens',
                        'description' => 'Configure colors, spacing and typography that match your brand.',
                    ],
                    [
                        'slug' => 'public-landing-layout',
                        'title' => 'Designing the Public Landing Page',
                        'description' => 'Build a hero, trust strip and featured courses grid.',
                    ],
                    [
                        'slug' => 'course-card-design',
                        'title' => 'Premium Course Card Design',
                        'description' => 'Create consistent 16:9 thumbnails and hover effects.',
                    ],
                    [
                        'slug' => 'alpine-interactions',
                        'title' => 'Alpine.js for Simple Interactions',
                        'description' => 'Add toggles, tabs and modals without heavy JavaScript.',
                    ],
                    [
                        'slug' => 'dark-mode-and-rtl',
                        'title' => 'Dark Mode & RTL Considerations',
                        'description' => 'Keep your UI readable in both light, dark and RTL layouts.',
                    ],
                ],
                'course-launch-marketing-blueprint' => [
                    [
                        'slug' => 'define-a-flagship',
                        'title' => 'Defining Your Flagship Course Offer',
                        'description' => 'Choose a clear transformation and promise for students.',
                    ],
                    [
                        'slug' => 'outline-and-curriculum',
                        'title' => 'Outlining Your Curriculum',
                        'description' => 'Turn your expertise into a structured, bingeable course.',
                    ],
                    [
                        'slug' => 'sales-page-copy',
                        'title' => 'Writing High-Converting Sales Page Copy',
                        'description' => 'Craft headlines, benefits and FAQs tailored to CourseFlow.',
                    ],
                    [
                        'slug' => 'launch-email-sequence',
                        'title' => 'Launch Email Sequences',
                        'description' => 'Plan pre-launch, launch and post-launch emails that convert.',
                    ],
                    [
                        'slug' => 'evergreen-funnels',
                        'title' => 'Evergreen Funnels into CourseFlow',
                        'description' => 'Connect your funnel tools so new students land directly in CourseFlow.',
                    ],
                ],
                'courseflow-arabic-rtl' => [
                    [
                        'slug' => 'arabic-language-setup',
                        'title' => 'Enabling Arabic & RTL Support',
                        'description' => 'Configure localization files and RTL CSS classes.',
                    ],
                    [
                        'slug' => 'translate-landing-page',
                        'title' => 'Translating the Landing Page',
                        'description' => 'Localize headlines, features and CTAs into Arabic.',
                    ],
                    [
                        'slug' => 'rtl-course-layout',
                        'title' => 'Designing RTL Course Layouts',
                        'description' => 'Ensure grids, cards and navigation feel natural in RTL.',
                    ],
                    [
                        'slug' => 'test-arabic-experience',
                        'title' => 'Testing the Arabic Student Experience',
                        'description' => 'Use Dusk to visually confirm RTL rendering.',
                    ],
                ],
                'courseflow-quickstart-mini-course' => [
                    [
                        'slug' => 'quickstart-overview',
                        'title' => 'Quickstart Overview',
                        'description' => 'See exactly what you will ship in the next 60 minutes.',
                    ],
                    [
                        'slug' => 'clone-and-install',
                        'title' => 'Clone, Install & Configure',
                        'description' => 'Clone the project, install dependencies and run migrations.',
                    ],
                    [
                        'slug' => 'seed-demo-data',
                        'title' => 'Seed Demo Data & Verify UI',
                        'description' => 'Load realistic demo courses, lessons and students.',
                    ],
                    [
                        'slug' => 'first-payment-test',
                        'title' => 'Run Your First Test Payment',
                        'description' => 'Walk through a full checkout from landing page to dashboard.',
                    ],
                ],
                'creator-productivity-systems' => [
                    [
                        'slug' => 'plan-content',
                        'title' => 'Planning Your Content Pipeline',
                        'description' => 'Turn scattered ideas into a repeatable content plan.',
                    ],
                    [
                        'slug' => 'batch-recording',
                        'title' => 'Batch Recording Sessions',
                        'description' => 'Record multiple lessons in one focused block.',
                    ],
                    [
                        'slug' => 'upload-and-organize',
                        'title' => 'Upload, Organize & Publish',
                        'description' => 'Upload videos, set positions and publish lessons on schedule.',
                    ],
                    [
                        'slug' => 'track-progress',
                        'title' => 'Track Student Progress',
                        'description' => 'Use CourseFlow progress data to see where students get stuck.',
                    ],
                    [
                        'slug' => 'optimize-routine',
                        'title' => 'Optimize Your Weekly Routine',
                        'description' => 'Protect time to improve courses while staying consistent.',
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
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'position' => $position + 1,
                        'status' => Lesson::STATUS_PUBLISHED,
                    ]
                );
            }
        }

        $enroll = new EnrollUserInCourseAction();
        $markLessonCompleted = new MarkLessonCompletedAction();

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
    }
}
