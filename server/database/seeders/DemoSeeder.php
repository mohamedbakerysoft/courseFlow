<?php

namespace Database\Seeders;

use App\Actions\Courses\EnrollUserInCourseAction;
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
        $instructor = User::updateOrCreate(
            ['email' => 'instructor@demo.com'],
            [
                'name' => 'Demo Instructor',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
                'bio' => 'Instructor for demo courses',
                'social_links' => ['twitter' => 'https://twitter.com/demo'],
            ]
        );

        $student = User::updateOrCreate(
            ['email' => 'student@demo.com'],
            [
                'name' => 'Demo Student',
                'password' => bcrypt('password'),
                'role' => User::ROLE_STUDENT,
            ]
        );

        $courseA = Course::updateOrCreate(
            ['slug' => 'demo-course-free'],
            [
                'title' => 'Demo Course A (Free)',
                'description' => 'A free demo course to explore the platform.',
                'thumbnail_path' => null,
                'price' => 0,
                'currency' => 'USD',
                'is_free' => true,
                'status' => Course::STATUS_PUBLISHED,
                'language' => 'en',
                'instructor_id' => $instructor->id,
            ]
        );

        $courseB = Course::updateOrCreate(
            ['slug' => 'demo-course-paid'],
            [
                'title' => 'Demo Course B (Paid)',
                'description' => 'A paid demo course with premium content.',
                'thumbnail_path' => null,
                'price' => 49.00,
                'currency' => 'USD',
                'is_free' => false,
                'status' => Course::STATUS_PUBLISHED,
                'language' => 'en',
                'instructor_id' => $instructor->id,
            ]
        );

        $lessonsA = [
            ['slug' => 'intro', 'title' => 'Introduction', 'position' => 1],
            ['slug' => 'setup', 'title' => 'Setup', 'position' => 2],
            ['slug' => 'basics', 'title' => 'Basics', 'position' => 3],
            ['slug' => 'next-steps', 'title' => 'Next Steps', 'position' => 4],
        ];
        foreach ($lessonsA as $data) {
            Lesson::updateOrCreate(
                ['course_id' => $courseA->id, 'slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'description' => '',
                    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                    'position' => $data['position'],
                    'status' => Lesson::STATUS_PUBLISHED,
                ]
            );
        }

        $lessonsB = [
            ['slug' => 'welcome', 'title' => 'Welcome', 'position' => 1],
            ['slug' => 'advanced-1', 'title' => 'Advanced Part 1', 'position' => 2],
            ['slug' => 'advanced-2', 'title' => 'Advanced Part 2', 'position' => 3],
            ['slug' => 'bonus', 'title' => 'Bonus Content', 'position' => 4],
        ];
        foreach ($lessonsB as $data) {
            Lesson::updateOrCreate(
                ['course_id' => $courseB->id, 'slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'description' => '',
                    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                    'position' => $data['position'],
                    'status' => Lesson::STATUS_PUBLISHED,
                ]
            );
        }

        $enroll = new EnrollUserInCourseAction();
        $enroll->execute($student, $courseA);
        $enroll->execute($student, $courseB);

        $firstLessonA = Lesson::where('course_id', $courseA->id)->orderBy('position')->first();
        if ($firstLessonA) {
            $student->completedLessons()->syncWithoutDetaching([
                $firstLessonA->id => ['completed_at' => Carbon::now()],
            ]);
        }

        Payment::updateOrCreate(
            [
                'user_id' => $student->id,
                'course_id' => $courseB->id,
                'provider' => 'stripe',
                'status' => Payment::STATUS_PAID,
            ],
            [
                'amount' => $courseB->price,
                'currency' => $courseB->currency,
                'stripe_session_id' => 'DEMO_STRIPE_SESSION_'.($student->id).'_'.$courseB->id,
                'external_reference' => 'DEMO-STRIPE-'.uniqid(),
            ]
        );

        Payment::updateOrCreate(
            [
                'user_id' => $student->id,
                'course_id' => $courseB->id,
                'provider' => 'manual',
                'status' => Payment::STATUS_PENDING,
            ],
            [
                'amount' => $courseB->price,
                'currency' => $courseB->currency,
                'proof_path' => null,
                'external_reference' => 'DEMO-MANUAL-'.uniqid(),
            ]
        );
    }
}

