<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstructorProfileController extends Controller
{
    public function edit(SettingsService $settings): View
    {
        $instructorName = (string) $settings->get('instructor.name', '');
        $instructorTitle = (string) $settings->get('instructor.title', '');
        $instructorBio = (string) $settings->get('instructor.bio', '');
        $heroHeadline = (string) $settings->get('instructor.hero_headline', (string) $settings->get('landing.hero_title', 'Teach and sell your courses with CourseFlow'));
        $heroSubheadline = (string) $settings->get('instructor.hero_subheadline', (string) $settings->get('landing.hero_subtitle', 'Launch a clean, modern course platform in minutes.'));
        $heroImageMode = (string) $settings->get('landing.hero_image_mode', 'contain');
        $socialTwitter = (string) $settings->get('instructor.social.twitter', '');
        $socialInstagram = (string) $settings->get('instructor.social.instagram', '');
        $socialYouTube = (string) $settings->get('instructor.social.youtube', '');
        $socialLinkedIn = (string) $settings->get('instructor.social.linkedin', '');

        return view('dashboard.instructor.edit', compact(
            'instructorName',
            'instructorTitle',
            'instructorBio',
            'heroHeadline',
            'heroSubheadline',
            'heroImageMode',
            'socialTwitter',
            'socialInstagram',
            'socialYouTube',
            'socialLinkedIn',
        ));
    }

    public function update(Request $request, SettingsService $settings): RedirectResponse
    {
        $validated = $request->validate([
            'instructor_name' => ['nullable', 'string', 'max:255'],
            'instructor_title' => ['nullable', 'string', 'max:255'],
            'instructor_bio' => ['nullable', 'string'],
            'hero_headline' => ['nullable', 'string', 'max:255'],
            'hero_subheadline' => ['nullable', 'string', 'max:255'],
            'hero_image_mode' => ['nullable', 'in:contain,cover'],
            'social_twitter' => ['nullable', 'url'],
            'social_instagram' => ['nullable', 'url'],
            'social_youtube' => ['nullable', 'url'],
            'social_linkedin' => ['nullable', 'url'],
        ]);

        $values = [
            'instructor.name' => $validated['instructor_name'] ?? '',
            'instructor.title' => $validated['instructor_title'] ?? '',
            'instructor.bio' => $validated['instructor_bio'] ?? '',
            'instructor.hero_headline' => $validated['hero_headline'] ?? '',
            'instructor.hero_subheadline' => $validated['hero_subheadline'] ?? '',
            'landing.hero_image_mode' => $validated['hero_image_mode'] ?? 'contain',
            'instructor.social.twitter' => $validated['social_twitter'] ?? '',
            'instructor.social.instagram' => $validated['social_instagram'] ?? '',
            'instructor.social.youtube' => $validated['social_youtube'] ?? '',
            'instructor.social.linkedin' => $validated['social_linkedin'] ?? '',
        ];

        $settings->set($values);

        return back()->with('status', 'Instructor profile updated.');
    }
}
