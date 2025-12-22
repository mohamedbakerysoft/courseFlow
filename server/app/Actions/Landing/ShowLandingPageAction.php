<?php

namespace App\Actions\Landing;

use App\Models\Course;
use App\Models\User;
use App\Services\SettingsService;

class ShowLandingPageAction
{
    public function __construct(
        protected SettingsService $settings
    ) {}

    public function execute(): array
    {
        $instructor = User::query()
            ->where('email', config('demo.admin_email', User::PROTECTED_ADMIN_EMAIL))
            ->first()
            ?: User::query()->where('role', User::ROLE_ADMIN)->first();

        $locale = app()->getLocale();
        $heroTitle = (string) (
            $this->settings->get("instructor.hero_headline_{$locale}")
            ?: $this->settings->get('instructor.hero_headline')
            ?: $this->settings->get("landing.hero_title_{$locale}")
            ?: $this->settings->get('landing.hero_title', 'Single‑Instructor LMS for Selling Courses')
        );
        $heroSubtitle = (string) (
            $this->settings->get("instructor.hero_subheadline_{$locale}")
            ?: $this->settings->get('instructor.hero_subheadline')
            ?: $this->settings->get("landing.hero_subtitle_{$locale}")
            ?: $this->settings->get('landing.hero_subtitle', 'For solo creators: sell courses with Stripe/PayPal, manual payments, and track student progress.')
        );

        $instructorName = (string) ($this->settings->get('instructor.name') ?: ($instructor?->name ?? 'Instructor'));
        $instructorTitle = (string) ($this->settings->get('instructor.title') ?: '');
        $instructorBio = (string) ($this->settings->get('instructor.bio') ?: ($instructor?->bio ?? ''));

        $instructorImagePath = (string) $this->settings->get('landing.instructor_image', '');
        $instructorImageUrl = $instructorImagePath !== '' ? asset('storage/'.$instructorImagePath) : null;

        $heroImageModeSetting = (string) $this->settings->get('landing.hero_image_mode', 'contain');
        $heroImageMode = in_array($heroImageModeSetting, ['contain', 'cover'], true) ? $heroImageModeSetting : 'contain';
        $heroImageFocusSetting = (string) $this->settings->get('landing.hero_image_focus', 'center');
        $heroImageFocus = in_array($heroImageFocusSetting, ['center', 'top', 'bottom', 'left', 'right'], true) ? $heroImageFocusSetting : 'center';

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

        return [
            'heroTitle' => $heroTitle,
            'heroSubtitle' => $heroSubtitle,
            'instructor' => $instructor,
            'instructorName' => $instructorName,
            'instructorTitle' => $instructorTitle,
            'instructorBio' => $instructorBio,
            'instructorImageUrl' => $instructorImageUrl,
            'instructorLinks' => $instructorLinks,
            'heroImageMode' => $heroImageMode,
            'heroImageFocus' => $heroImageFocus,
            'showHero' => $showHero,
            'showAboutInstructor' => $showAboutInstructor,
            'showCoursesPreview' => $showCoursesPreview,
            'showTestimonials' => $showTestimonials,
            'showFooterCta' => $showFooterCta,
            'showContactForm' => $showContactForm,
            'features' => $features,
            'featuredCourses' => $featuredCourses,
        ];
    }
}
