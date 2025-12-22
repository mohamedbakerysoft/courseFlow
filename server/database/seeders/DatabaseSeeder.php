<?php

namespace Database\Seeders;

use App\Models\Course;
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

        Course::updateOrCreate(
            ['slug' => 'sample-course'],
            [
                'title' => 'Sample Course',
                'thumbnail_path' => null,
                'description' => 'Sample course description',
                'price' => 0,
                'currency' => 'USD',
                'is_free' => true,
                'status' => Course::STATUS_PUBLISHED,
                'language' => 'en',
                'instructor_id' => User::query()->where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))->first()?->id,
            ],
        );

        User::query()->where('role', User::ROLE_ADMIN)->update([
            'bio' => 'Instructor bio',
            'social_links' => ['twitter' => 'https://twitter.com/example'],
        ]);
    }
}
