<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSeederIdempotentTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_runs_twice_without_duplication(): void
    {
        config()->set('demo.enabled', true);

        $this->seed(DemoSeeder::class);

        $coursesBefore = Course::where('slug', 'like', 'demo-course-%')->count();
        $lessonsBefore = Lesson::whereHas('course', function ($q) {
            $q->where('slug', 'like', 'demo-course-%');
        })->count();
        $studentsBefore = User::where('email', 'like', 'student%@demo.com')
            ->orWhere('email', 'student@demo.com')
            ->count();

        $this->seed(DemoSeeder::class);

        $coursesAfter = Course::where('slug', 'like', 'demo-course-%')->count();
        $lessonsAfter = Lesson::whereHas('course', function ($q) {
            $q->where('slug', 'like', 'demo-course-%');
        })->count();
        $studentsAfter = User::where('email', 'like', 'student%@demo.com')
            ->orWhere('email', 'student@demo.com')
            ->count();

        $this->assertSame($coursesBefore, $coursesAfter);
        $this->assertSame($lessonsBefore, $lessonsAfter);
        $this->assertSame($studentsBefore, $studentsAfter);
    }
}
