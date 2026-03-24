<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\InstructorProfile\UpdateInstructorProfileRequest;
use App\Services\SettingsService;
use App\Support\MediaAsset;
use Illuminate\Http\RedirectResponse;
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
        $pageLabel = (string) $settings->get('instructor.page_label', 'My profile');
        $catalogLabel = (string) $settings->get('instructor.catalog_label', 'My courses');
        $catalogHeading = (string) $settings->get('instructor.catalog_heading', 'Learn with me');
        $catalogDescription = (string) $settings->get('instructor.catalog_description', 'Browse the published course library below to find the right entry point for your current level and goals.');
        $primaryCtaLabel = (string) $settings->get('instructor.primary_cta_label', 'Browse top course');
        $secondaryCtaLabel = (string) $settings->get('instructor.secondary_cta_label', 'View all courses');
        $bestForText = (string) $settings->get('instructor.best_for_text', 'Creators who want a clearer path from learning to launch');
        $focusAreas = (string) $settings->get('instructor.focus_areas', "Course launches\nStudent onboarding\nPayments and enrollment\nLaravel course products");
        $expectationsLabel = (string) $settings->get('instructor.expectations_label', 'Why learn here');
        $expectationsHeading = (string) $settings->get('instructor.expectations_heading', 'What students can expect');
        $expectations = (string) $settings->get('instructor.expectations', "A clearer path from browsing a course to actually finishing lessons and applying what was learned.\nCourses designed around practical implementation, not filler content or abstract theory only.\nA catalog that supports different experience levels while staying consistent in quality and structure.");
        $socialWebsite = (string) $settings->get('instructor.social.website', '');
        $socialTwitter = (string) $settings->get('instructor.social.twitter', '');
        $socialInstagram = (string) $settings->get('instructor.social.instagram', '');
        $socialYouTube = (string) $settings->get('instructor.social.youtube', '');
        $socialLinkedIn = (string) $settings->get('instructor.social.linkedin', '');
        $socialFacebook = (string) $settings->get('instructor.social.facebook', '');
        return view('dashboard.instructor.edit', compact(
            'instructorName',
            'instructorTitle',
            'instructorBio',
            'instructorImageUrl',
            'heroHeadline',
            'heroSubheadline',
            'pageLabel',
            'catalogLabel',
            'catalogHeading',
            'catalogDescription',
            'primaryCtaLabel',
            'secondaryCtaLabel',
            'bestForText',
            'focusAreas',
            'expectationsLabel',
            'expectationsHeading',
            'expectations',
            'socialWebsite',
            'socialTwitter',
            'socialInstagram',
            'socialYouTube',
            'socialLinkedIn',
            'socialFacebook',
        ));
    }

    public function update(UpdateInstructorProfileRequest $request, SettingsService $settings): RedirectResponse
    {
        $validated = $request->validated();

        $values = [
            'instructor.name' => $validated['instructor_name'] ?? '',
            'instructor.title' => $validated['instructor_title'] ?? '',
            'instructor.bio' => $validated['instructor_bio'] ?? '',
            'instructor.hero_headline' => $validated['hero_headline'] ?? '',
            'instructor.hero_subheadline' => $validated['hero_subheadline'] ?? '',
            'instructor.page_label' => $validated['page_label'] ?? '',
            'instructor.catalog_label' => $validated['catalog_label'] ?? '',
            'instructor.catalog_heading' => $validated['catalog_heading'] ?? '',
            'instructor.catalog_description' => $validated['catalog_description'] ?? '',
            'instructor.primary_cta_label' => $validated['primary_cta_label'] ?? '',
            'instructor.secondary_cta_label' => $validated['secondary_cta_label'] ?? '',
            'instructor.best_for_text' => $validated['best_for_text'] ?? '',
            'instructor.focus_areas' => $validated['focus_areas'] ?? '',
            'instructor.expectations_label' => $validated['expectations_label'] ?? '',
            'instructor.expectations_heading' => $validated['expectations_heading'] ?? '',
            'instructor.expectations' => $validated['expectations'] ?? '',
            'instructor.social.website' => $validated['social_website'] ?? '',
            'instructor.social.twitter' => $validated['social_twitter'] ?? '',
            'instructor.social.instagram' => $validated['social_instagram'] ?? '',
            'instructor.social.youtube' => $validated['social_youtube'] ?? '',
            'instructor.social.linkedin' => $validated['social_linkedin'] ?? '',
            'instructor.social.facebook' => $validated['social_facebook'] ?? '',
        ];

        if ($request->hasFile('instructor_image')) {
            $path = $request->file('instructor_image')->store('landing', 'public');
            $values['landing.instructor_image'] = $path;
        }

        $settings->set($values);

        return back()->with('status', 'Instructor profile updated.');
    }
}
