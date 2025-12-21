<?php

namespace App\Actions\Landing;

use App\Models\Course;
use App\Models\User;
use App\Services\SettingsService;

class ShowLandingPageAction
{
    public function __construct(
        protected SettingsService $settings
    ) {
    }

    public function execute(): array
    {
        $instructor = User::query()->where('role', User::ROLE_ADMIN)->first();

        $heroTitle = (string) ($this->settings->get('instructor.hero_headline') ?: $this->settings->get('landing.hero_title', 'Teach and sell your courses with CourseFlow'));
        $heroSubtitle = (string) ($this->settings->get('instructor.hero_subheadline') ?: $this->settings->get('landing.hero_subtitle', 'Launch a clean, modern course platform in minutes.'));

        $instructorName = (string) ($this->settings->get('instructor.name') ?: ($instructor?->name ?? 'Instructor'));
        $instructorTitle = (string) ($this->settings->get('instructor.title') ?: '');
        $instructorBio = (string) ($this->settings->get('instructor.bio') ?: ($instructor?->bio ?? ''));

        $instructorImagePath = (string) $this->settings->get('landing.instructor_image', '');
        $instructorImageUrl = $instructorImagePath !== '' ? asset('storage/'.$instructorImagePath) : null;

        $heroImageModeSetting = (string) $this->settings->get('landing.hero_image_mode', 'contain');
        $heroImageMode = in_array($heroImageModeSetting, ['contain', 'cover'], true) ? $heroImageModeSetting : 'contain';

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
            'features' => $features,
            'featuredCourses' => $featuredCourses,
        ];
    }
}
