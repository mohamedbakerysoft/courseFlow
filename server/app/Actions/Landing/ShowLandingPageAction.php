<?php

namespace App\Actions\Landing;

use App\Models\Course;
use App\Models\User;
use App\Services\SettingsService;
use App\Support\LandingContent;
use App\Support\MediaAsset;
use Illuminate\View\View;

class ShowLandingPageAction
{
    public function __construct(
        protected SettingsService $settings
    ) {}

    public function execute(): View
    {
        $instructor = User::query()
            ->where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))
            ->first()
            ?: User::query()->where('role', User::ROLE_ADMIN)->first();

        $heroTitleLocal = (string) ($this->settings->get('instructor.hero_headline_en') ?: $this->settings->get('instructor.hero_headline') ?: $this->settings->get('hero.title.en') ?: $this->settings->get('landing.hero_title_en') ?: '');
        $heroSubtitleLocal = (string) ($this->settings->get('instructor.hero_subheadline_en') ?: $this->settings->get('instructor.hero_subheadline') ?: $this->settings->get('hero.subtitle.en') ?: $this->settings->get('landing.hero_subtitle_en') ?: '');
        $heroTitleDefault = (string) $this->settings->get('landing.hero_title', 'Launch courses with a storefront learners trust');
        $heroSubtitleDefault = (string) $this->settings->get('landing.hero_subtitle', 'Learnova helps independent instructors sell digital courses with secure checkout, instant access, and progress-aware lessons.');
        $heroTitle = $heroTitleLocal !== '' ? $heroTitleLocal : $heroTitleDefault;
        $heroSubtitle = $heroSubtitleLocal !== '' ? $heroSubtitleLocal : $heroSubtitleDefault;

        $instructorName = (string) ($this->settings->get('instructor.name') ?: ($instructor?->name ?? 'Instructor'));
        $instructorTitle = (string) ($this->settings->get('instructor.title') ?: '');
        $instructorBio = (string) ($this->settings->get('instructor.bio') ?: ($instructor?->bio ?? ''));

        $heroImagePath = (string) (
            $this->settings->get('hero.image')
            ?: $this->settings->get('landing.instructor_image', '')
        );
        $heroImageUrl = MediaAsset::url($heroImagePath, 'images/demo/real/hero-formal-2.jpg');

        $heroImageFitSetting = (string) ($this->settings->get('hero.image_fit') ?: $this->settings->get('landing.hero_image_mode', 'cover'));
        $heroImageMode = in_array($heroImageFitSetting, ['contain', 'cover'], true) ? $heroImageFitSetting : 'contain';
        $heroImageFocusSetting = (string) ($this->settings->get('hero.image_focus') ?: $this->settings->get('landing.hero_image_focus', 'center'));
        $heroImageFocus = in_array($heroImageFocusSetting, ['center', 'top', 'bottom', 'left', 'right'], true) ? $heroImageFocusSetting : 'center';
        $heroImageRatioSetting = (string) ($this->settings->get('hero.image_ratio') ?: '4:5');
        $heroImageRatio = match ($heroImageRatioSetting) {
            '4:5' => '4/5',
            '1:1' => '1/1',
            default => '16/9',
        };
        $heroImageWidthVal = (int) ($this->settings->get('hero.image_width') ?: 0);
        $heroImageHeightVal = (int) ($this->settings->get('hero.image_height') ?: 0);
        $heroImageWidth = $heroImageWidthVal > 0 ? $heroImageWidthVal : null;
        $heroImageHeight = $heroImageHeightVal > 0 ? $heroImageHeightVal : null;

        $showHero = (bool) $this->settings->get('landing.show_hero', true);
        $showPlatformProof = (bool) $this->settings->get('landing.show_platform_proof', true);
        $showAboutInstructor = (bool) $this->settings->get('landing.show_about', true);
        $showCoursesPreview = (bool) $this->settings->get('landing.show_courses_preview', true);
        $showProblemSection = (bool) $this->settings->get('landing.show_problem_section', true);
        $showFlowSection = (bool) $this->settings->get('landing.show_flow_section', true);
        $showTestimonials = (bool) $this->settings->get('landing.show_testimonials', true);
        $showFaqSection = (bool) $this->settings->get('landing.show_faq_section', true);
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
        $landingFaqs = LandingContent::faqs($this->settings);
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
                'title' => (string) $this->settings->get('landing.feature_3_title', 'Stronger instructor trust'),
                'description' => (string) $this->settings->get('landing.feature_3_description', 'Show a real instructor, a clear catalog, and the details learners need before they buy.'),
                'icon' => '✨',
            ],
        ];

        $featuredCourses = Course::query()
            ->published()
            ->where('product_type', Course::TYPE_COURSE)
            ->with('instructor')
            ->withCount('lessons')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
        $heroCourses = $featuredCourses->take(4)->values();

        $data = [
            'heroTitle' => $heroTitle,
            'heroSubtitle' => $heroSubtitle,
            'instructor' => $instructor,
            'instructorName' => $instructorName,
            'instructorTitle' => $instructorTitle,
            'instructorBio' => $instructorBio,
            'heroImageUrl' => $heroImageUrl,
            'instructorLinks' => $instructorLinks,
            'heroImageMode' => $heroImageMode,
            'heroImageFocus' => $heroImageFocus,
            'heroImageRatio' => $heroImageRatio,
            'heroImageWidth' => $heroImageWidth,
            'heroImageHeight' => $heroImageHeight,
            'showHero' => $showHero,
            'showPlatformProof' => $showPlatformProof,
            'showAboutInstructor' => $showAboutInstructor,
            'showCoursesPreview' => $showCoursesPreview,
            'showProblemSection' => $showProblemSection,
            'showFlowSection' => $showFlowSection,
            'showTestimonials' => $showTestimonials,
            'showFaqSection' => $showFaqSection,
            'showFooterCta' => $showFooterCta,
            'showContactForm' => $showContactForm,
            'features' => $features,
            'featuredCourses' => $featuredCourses,
            'heroCourses' => $heroCourses,
            'landingCopy' => $landingCopy,
            'landingTestimonials' => $landingTestimonials,
            'landingFaqs' => $landingFaqs,
            'heroVideoUrl' => $heroVideoUrl,
        ];

        $layoutSetting = (string) ($this->settings->get('landing.layout') ?? 'default');
        $layoutView = match ($layoutSetting) {
            'default' => 'default',
            'layout_v2' => 'v2',
            'layout_v3' => 'v3',
            default => 'default',
        };

        return view("landing.layouts.$layoutView", $data);
    }
}
