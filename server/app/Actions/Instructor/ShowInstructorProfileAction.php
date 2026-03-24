<?php

namespace App\Actions\Instructor;

use App\Models\Course;
use App\Models\User;
use App\Services\SettingsService;

class ShowInstructorProfileAction
{
    public function __construct(
        protected SettingsService $settings,
    ) {}

    public function execute(): array
    {
        $instructor = User::primaryInstructorOrFail();
        $courses = Course::paginatePublishedForInstructorProfile(20);

        $links = [];
        $settingsLinks = [
            'website' => (string) $this->settings->get('instructor.social.website', ''),
            'twitter' => (string) $this->settings->get('instructor.social.twitter', ''),
            'instagram' => (string) $this->settings->get('instructor.social.instagram', ''),
            'youtube' => (string) $this->settings->get('instructor.social.youtube', ''),
            'linkedin' => (string) $this->settings->get('instructor.social.linkedin', ''),
            'facebook' => (string) $this->settings->get('instructor.social.facebook', ''),
        ];

        $links = array_filter($settingsLinks, fn ($value) => trim((string) $value) !== '');

        if (empty($links) && ! empty($instructor->social_links)) {
            $links = is_array($instructor->social_links)
                ? $instructor->social_links
                : json_decode($instructor->social_links, true) ?? [];
        }

        $content = [
            'page_label' => (string) $this->settings->get('instructor.page_label', 'My profile'),
            'hero_headline' => (string) $this->settings->get('instructor.hero_headline', $instructor->name),
            'hero_subheadline' => (string) $this->settings->get('instructor.hero_subheadline', (string) ($this->settings->get('instructor.bio', $instructor->bio ?? ''))),
            'catalog_label' => (string) $this->settings->get('instructor.catalog_label', 'My courses'),
            'catalog_heading' => (string) $this->settings->get('instructor.catalog_heading', 'Learn with me'),
            'catalog_description' => (string) $this->settings->get('instructor.catalog_description', 'Browse the published course library below to find the right entry point for your current level and goals.'),
            'primary_cta_label' => (string) $this->settings->get('instructor.primary_cta_label', 'Browse top course'),
            'secondary_cta_label' => (string) $this->settings->get('instructor.secondary_cta_label', 'View all courses'),
            'best_for_text' => (string) $this->settings->get('instructor.best_for_text', 'Creators who want a clearer path from learning to launch'),
            'focus_areas' => array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', (string) $this->settings->get('instructor.focus_areas', 'Course launches, Student onboarding, Payments and enrollment, Laravel course products'))))),
            'expectations' => array_values(array_filter(array_map('trim', preg_split('/[\r\n]+/', (string) $this->settings->get('instructor.expectations', "A clearer path from browsing a course to actually finishing lessons and applying what was learned.\nCourses designed around practical implementation, not filler content or abstract theory only.\nA catalog that supports different experience levels while staying consistent in quality and structure."))))),
            'expectations_label' => (string) $this->settings->get('instructor.expectations_label', 'Why learn here'),
            'expectations_heading' => (string) $this->settings->get('instructor.expectations_heading', 'What students can expect'),
        ];

        return [$instructor, $courses, $links, $content];
    }
}
