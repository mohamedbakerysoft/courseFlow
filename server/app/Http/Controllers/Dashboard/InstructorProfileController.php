<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Support\MediaAsset;
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
        $instructorImagePath = (string) $settings->get('landing.instructor_image', '');
        $instructorImageUrl = MediaAsset::url($instructorImagePath, MediaAsset::avatarFallbackPath($instructorName));
        $heroHeadline = (string) $settings->get('instructor.hero_headline', (string) $settings->get('landing.hero_title', 'Launch courses with a storefront learners trust'));
        $heroSubheadline = (string) $settings->get('instructor.hero_subheadline', (string) $settings->get('landing.hero_subtitle', 'Sell digital courses with secure checkout, instant access, and structured lessons.'));
        $socialTwitter = (string) $settings->get('instructor.social.twitter', '');
        $socialInstagram = (string) $settings->get('instructor.social.instagram', '');
        $socialYouTube = (string) $settings->get('instructor.social.youtube', '');
        $socialLinkedIn = (string) $settings->get('instructor.social.linkedin', '');
        $heroImageFitSetting = (string) ($settings->get('hero.image_fit') ?: $settings->get('landing.hero_image_mode', 'cover'));
        $heroImageMode = in_array($heroImageFitSetting, ['contain', 'cover'], true) ? $heroImageFitSetting : 'contain';

        return view('dashboard.instructor.edit', compact(
            'instructorName',
            'instructorTitle',
            'instructorBio',
            'instructorImageUrl',
            'heroHeadline',
            'heroSubheadline',
            'socialTwitter',
            'socialInstagram',
            'socialYouTube',
            'socialLinkedIn',
            'heroImageMode',
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
            'social_twitter' => ['nullable', 'url'],
            'social_instagram' => ['nullable', 'url'],
            'social_youtube' => ['nullable', 'url'],
            'social_linkedin' => ['nullable', 'url'],
            'hero_image_mode' => ['nullable', 'in:contain,cover'],
            'instructor_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $values = [
            'instructor.name' => $validated['instructor_name'] ?? '',
            'instructor.title' => $validated['instructor_title'] ?? '',
            'instructor.bio' => $validated['instructor_bio'] ?? '',
            'instructor.hero_headline' => $validated['hero_headline'] ?? '',
            'instructor.hero_subheadline' => $validated['hero_subheadline'] ?? '',
            'instructor.social.twitter' => $validated['social_twitter'] ?? '',
            'instructor.social.instagram' => $validated['social_instagram'] ?? '',
            'instructor.social.youtube' => $validated['social_youtube'] ?? '',
            'instructor.social.linkedin' => $validated['social_linkedin'] ?? '',
        ];
        if (array_key_exists('hero_image_mode', $validated)) {
            $values['landing.hero_image_mode'] = $validated['hero_image_mode'];
        }

        if ($request->hasFile('instructor_image')) {
            $path = $request->file('instructor_image')->store('landing', 'public');
            $values['landing.instructor_image'] = $path;
        }

        $settings->set($values);

        return back()->with('status', 'Instructor profile updated.');
    }
}
