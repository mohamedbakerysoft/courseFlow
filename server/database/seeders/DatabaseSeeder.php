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

        Course::firstOrCreate(
            ['title' => 'Sample Course'],
            ['thumbnail_path' => null, 'status' => Course::STATUS_PUBLISHED],
        );

        User::query()->where('role', User::ROLE_ADMIN)->update([
            'bio' => 'Instructor bio',
            'social_links' => ['twitter' => 'https://twitter.com/example'],
        ]);
    }
}
