<?php

namespace App\Actions\Landing;

use App\Models\Course;
use App\Models\User;
use App\Services\SettingsService;
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

        $locale = app()->getLocale();
        $heroTitleLocal = (string) ($this->settings->get("instructor.hero_headline_{$locale}") ?: $this->settings->get('instructor.hero_headline') ?: $this->settings->get("hero.title.{$locale}") ?: $this->settings->get("landing.hero_title_{$locale}") ?: '');
        $heroSubtitleLocal = (string) ($this->settings->get("instructor.hero_subheadline_{$locale}") ?: $this->settings->get('instructor.hero_subheadline') ?: $this->settings->get("hero.subtitle.{$locale}") ?: $this->settings->get("landing.hero_subtitle_{$locale}") ?: '');
        $heroTitleFallback = (string) ($locale === 'ar' ? ($this->settings->get('hero.title.en') ?: $this->settings->get('landing.hero_title_en') ?: '') : ($this->settings->get('hero.title.ar') ?: $this->settings->get('landing.hero_title_ar') ?: ''));
        $heroSubtitleFallback = (string) ($locale === 'ar' ? ($this->settings->get('hero.subtitle.en') ?: $this->settings->get('landing.hero_subtitle_en') ?: '') : ($this->settings->get('hero.subtitle.ar') ?: $this->settings->get('landing.hero_subtitle_ar') ?: ''));
        $heroTitleDefault = (string) $this->settings->get('landing.hero_title', 'Single‑Instructor LMS for Selling Courses');
        $heroSubtitleDefault = (string) $this->settings->get('landing.hero_subtitle', 'For solo creators: sell courses with Stripe/PayPal, manual payments, and track student progress.');
        $heroTitle = $heroTitleLocal !== '' ? $heroTitleLocal : ($heroTitleFallback !== '' ? $heroTitleFallback : $heroTitleDefault);
        $heroSubtitle = $heroSubtitleLocal !== '' ? $heroSubtitleLocal : ($heroSubtitleFallback !== '' ? $heroSubtitleFallback : $heroSubtitleDefault);

        $instructorName = (string) ($this->settings->get('instructor.name') ?: ($instructor?->name ?? 'Instructor'));
        $instructorTitle = (string) ($this->settings->get('instructor.title') ?: '');
        $instructorBio = (string) ($this->settings->get('instructor.bio') ?: ($instructor?->bio ?? ''));

        $heroImagePath = (string) (
            $this->settings->get('hero.image')
            ?: $this->settings->get('landing.instructor_image', '')
        );
        $heroImageUrl = $heroImagePath !== '' ? asset('storage/'.$heroImagePath) : null;

        $heroImageFitSetting = (string) ($this->settings->get('hero.image_fit') ?: $this->settings->get('landing.hero_image_mode', 'contain'));
        $heroImageMode = in_array($heroImageFitSetting, ['contain', 'cover'], true) ? $heroImageFitSetting : 'contain';
        $heroImageFocusSetting = (string) ($this->settings->get('hero.image_focus') ?: $this->settings->get('landing.hero_image_focus', 'center'));
        $heroImageFocus = in_array($heroImageFocusSetting, ['center', 'top', 'bottom', 'left', 'right'], true) ? $heroImageFocusSetting : 'center';
        $heroImageRatioSetting = (string) ($this->settings->get('hero.image_ratio') ?: '16:9');
        $heroImageRatio = match ($heroImageRatioSetting) {
            '4:5' => '4/5',
            '1:1' => '1/1',
            default => '16/9',
        };

        $showHero = (bool) $this->settings->get('landing.show_hero', true);
        $showAboutInstructor = (bool) $this->settings->get('landing.show_about', true);
        $showCoursesPreview = (bool) $this->settings->get('landing.show_courses_preview', true);
        $showTestimonials = (bool) $this->settings->get('landing.show_testimonials', true);
        $showFooterCta = (bool) $this->settings->get('landing.show_footer_cta', true);
        $rawContact = $this->settings->get('landing.show_contact_form', false);
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

        $features = [
            [
                'title' => (string) $this->settings->get('landing.feature_1_title', 'Launch quickly'),
                'description' => (string) $this->settings->get('landing.feature_1_description', 'Ship a polished learning platform without building everything from scratch.'),
                'icon' => '⚡',
            ],
            [
                'title' => (string) $this->settings->get('landing.feature_2_title', 'Sell courses with confidence'),
                'description' => (string) $this->settings->get('landing.feature_2_description', 'Stripe, PayPal and manual payments are ready for production.'),
                'icon' => '💳',
            ],
            [
                'title' => (string) $this->settings->get('landing.feature_3_title', 'Delight your students'),
                'description' => (string) $this->settings->get('landing.feature_3_description', 'Clean lessons, progress tracking and RTL-ready layouts out of the box.'),
                'icon' => '🎓',
            ],
        ];

        $featuredCourses = Course::query()
            ->published()
            ->with('instructor')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

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
            'showHero' => $showHero,
            'showAboutInstructor' => $showAboutInstructor,
            'showCoursesPreview' => $showCoursesPreview,
            'showTestimonials' => $showTestimonials,
            'showFooterCta' => $showFooterCta,
            'showContactForm' => $showContactForm,
            'features' => $features,
            'featuredCourses' => $featuredCourses,
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
