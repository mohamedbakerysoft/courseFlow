<?php

namespace App\Actions\Landing;

use App\Models\Course;
use App\Models\User;
use App\Services\SettingsService;
use App\Support\LandingContent;
use Illuminate\View\View;

class ShowLandingPageAction
{
    public function __construct(
        protected SettingsService $settings
    ) {}

    public function execute(): View
    {
        $instructor = User::primaryInstructor();

        $heroTitleLocal = (string) ($this->settings->get('instructor.hero_headline_en') ?: $this->settings->get('instructor.hero_headline') ?: $this->settings->get('hero.title.en') ?: $this->settings->get('landing.hero_title_en') ?: '');
        $heroSubtitleLocal = (string) ($this->settings->get('instructor.hero_subheadline_en') ?: $this->settings->get('instructor.hero_subheadline') ?: $this->settings->get('hero.subtitle.en') ?: $this->settings->get('landing.hero_subtitle_en') ?: '');
        $heroTitleDefault = (string) $this->settings->get('landing.hero_title', 'Launch courses with a storefront learners trust');
        $heroSubtitleDefault = (string) $this->settings->get('landing.hero_subtitle', 'Launch in minutes with zero coding required. Sell digital courses with secure checkout, instant access, and structured lessons inside one clean experience.');
        $heroTitle = $heroTitleLocal !== '' ? $heroTitleLocal : $heroTitleDefault;
        $heroSubtitle = $heroSubtitleLocal !== '' ? $heroSubtitleLocal : $heroSubtitleDefault;

        $instructorName = (string) ($this->settings->get('instructor.name') ?: ($instructor?->name ?? 'Instructor'));
        $instructorTitle = (string) ($this->settings->get('instructor.title') ?: '');
        $instructorBio = (string) ($this->settings->get('instructor.bio') ?: ($instructor?->bio ?? ''));

        $showHero = (bool) $this->settings->get('landing.show_hero', true);
        $showPlatformProof = (bool) $this->settings->get('landing.show_platform_proof', true);
        $showAboutInstructor = (bool) $this->settings->get('landing.show_about', true);
        $showCoursesPreview = (bool) $this->settings->get('landing.show_courses_preview', true);
        $showProblemSection = (bool) $this->settings->get('landing.show_problem_section', true);
        $showFlowSection = (bool) $this->settings->get('landing.show_flow_section', true);
        $showTestimonials = (bool) $this->settings->get('landing.show_testimonials', true);
        $showFooterCta = (bool) $this->settings->get('landing.show_footer_cta', true);
        $rawContact = $this->settings->get('landing.show_contact_form', true);
        $showContactForm = false;
        if (is_bool($rawContact)) {
            $showContactForm = $rawContact;
        } else {
            $val = strtolower(trim((string) $rawContact));
            $showContactForm = in_array($val, ['1', 'true', 'on', 'yes'], true);
        }

        $settingsLinks = [
            'twitter' => (string) ($this->settings->get('instructor.social.twitter') ?: ''),
            'instagram' => (string) ($this->settings->get('instructor.social.instagram') ?: ''),
            'youtube' => (string) ($this->settings->get('instructor.social.youtube') ?: ''),
            'linkedin' => (string) ($this->settings->get('instructor.social.linkedin') ?: ''),
        ];
        $userLinks = [];
        if ($instructor && ! empty($instructor->social_links)) {
            $userLinks = is_array($instructor->social_links)
                ? $instructor->social_links
                : json_decode($instructor->social_links, true) ?? [];
        }
        $instructorLinks = [];
        foreach (['twitter', 'instagram', 'youtube', 'linkedin'] as $key) {
            $instructorLinks[$key] = $settingsLinks[$key] ?: ($userLinks[$key] ?? '');
        }
        $landingCopy = LandingContent::copy($this->settings);
        $landingTestimonials = LandingContent::testimonials($this->settings);
        $heroVideoUrl = (string) $this->settings->get('landing.hero_video_url', $instructorLinks['youtube'] ?? '');

        $features = [
            [
                'title' => (string) $this->settings->get('landing.feature_1_title', 'Secure checkout'),
                'description' => (string) $this->settings->get('landing.feature_1_description', 'Accept card, PayPal, or manual payments inside one clear checkout flow.'),
                'icon' => '💳',
            ],
            [
                'title' => (string) $this->settings->get('landing.feature_2_title', 'Structured delivery'),
                'description' => (string) $this->settings->get('landing.feature_2_description', 'Protected lessons, saved progress, and a clear learning path help students stay focused.'),
                'icon' => '📚',
            ],
            [
                'title' => (string) $this->settings->get('landing.feature_3_title', 'Fully modular design'),
                'description' => (string) $this->settings->get('landing.feature_3_description', 'Hide, move, or customize every single section directly from the admin dashboard without writing a single line of code.'),
                'icon' => '🎨',
            ],
        ];

        $featuredCourses = Course::listPublishedForLanding();
        $heroCourses = $featuredCourses->take(4)->values();

        $data = [
            'heroTitle' => $heroTitle,
            'heroSubtitle' => $heroSubtitle,
            'instructor' => $instructor,
            'instructorName' => $instructorName,
            'instructorTitle' => $instructorTitle,
            'instructorBio' => $instructorBio,
            'instructorLinks' => $instructorLinks,
            'showHero' => $showHero,
            'showPlatformProof' => $showPlatformProof,
            'showAboutInstructor' => $showAboutInstructor,
            'showCoursesPreview' => $showCoursesPreview,
            'showProblemSection' => $showProblemSection,
            'showFlowSection' => $showFlowSection,
            'showTestimonials' => $showTestimonials,
            'showFooterCta' => $showFooterCta,
            'showContactForm' => $showContactForm,
            'features' => $features,
            'featuredCourses' => $featuredCourses,
            'heroCourses' => $heroCourses,
            'landingCopy' => $landingCopy,
            'landingTestimonials' => $landingTestimonials,
            'heroVideoUrl' => $heroVideoUrl,
        ];

        return view('landing.layouts.default', $data);
    }
}
