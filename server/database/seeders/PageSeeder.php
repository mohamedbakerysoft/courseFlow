<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(['slug' => 'about'], [
            'title' => 'About the Instructor',
            'content' => 'This page describes the instructor background and experience.',
        ]);

        Page::updateOrCreate(['slug' => 'terms'], [
            'title' => 'Terms & Conditions',
            'content' => 'Terms and conditions governing platform usage.',
        ]);

        Page::updateOrCreate(['slug' => 'privacy'], [
            'title' => 'Privacy Policy',
            'content' => 'Privacy practices regarding user data.',
        ]);
    }
}
