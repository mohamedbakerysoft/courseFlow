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

        $coursesData = [
            ['slug' => 'demo-course-1', 'title' => 'Getting Started with CourseFlow', 'is_free' => true, 'price' => 0],
            ['slug' => 'demo-course-2', 'title' => 'Tailwind Layouts and Components', 'is_free' => true, 'price' => 0],
            ['slug' => 'demo-course-3', 'title' => 'Laravel Basics for Beginners', 'is_free' => false, 'price' => 29],
            ['slug' => 'demo-course-4', 'title' => 'Intermediate Laravel & Actions', 'is_free' => false, 'price' => 49],
            ['slug' => 'demo-course-5', 'title' => 'Payments & Checkout Flows', 'is_free' => false, 'price' => 59],
            ['slug' => 'demo-course-6', 'title' => 'Lesson Progress & UX Patterns', 'is_free' => true, 'price' => 0],
            ['slug' => 'demo-course-7', 'title' => 'Localization & RTL Support', 'is_free' => false, 'price' => 39],
        ];

        $createdCourses = [];
        foreach ($coursesData as $i => $c) {
            $course = Course::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'title' => $c['title'],
                    'description' => 'Demo content for '.$c['title'],
                    'thumbnail_path' => 'images/demo/course-'.($i+1).'.svg',
                    'price' => $c['price'],
                    'currency' => 'USD',
                    'is_free' => $c['is_free'],
                    'status' => Course::STATUS_PUBLISHED,
                    'language' => 'en',
                    'instructor_id' => $instructor->id,
                ]
            );
            $createdCourses[] = $course;
            $lessonCount = [4,5,6,7,8][($i % 5)];
            for ($p = 1; $p <= $lessonCount; $p++) {
                Lesson::updateOrCreate(
                    ['course_id' => $course->id, 'slug' => 'lesson-'.$p],
                    [
                        'title' => 'Lesson '.$p,
                        'description' => 'Lesson '.$p.' description',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'position' => $p,
                        'status' => Lesson::STATUS_PUBLISHED,
                    ]
                );
            }
        }

        $students = [$student];
        for ($s = 1; $s <= 14; $s++) {
            $students[] = User::updateOrCreate(
                ['email' => 'student'.$s.'@demo.com'],
                [
                    'name' => 'Student '.$s,
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_STUDENT,
                ]
            );
        }

        $enroll = new EnrollUserInCourseAction();
        foreach ($students as $idx => $stu) {
            foreach ($createdCourses as $ci => $course) {
                if (($idx + $ci) % 2 === 0) {
                    $enroll->execute($stu, $course);
                    $firstLesson = Lesson::where('course_id', $course->id)->orderBy('position')->first();
                    if ($firstLesson) {
                        $stu->completedLessons()->syncWithoutDetaching([
                            $firstLesson->id => ['completed_at' => Carbon::now()],
                        ]);
                    }
                }
            }
        }
    }
}
