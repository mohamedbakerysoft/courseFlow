<?php

namespace App\Support;

use App\Services\SettingsService;

class LandingContent
{
    public const COPY_DEFAULTS = [
        'hero_kicker' => 'Independent course business platform',
        'hero_video_eyebrow' => 'Platform walkthrough',
        'hero_video_title' => 'See the storefront, course page, and enrollment flow together in one clean preview.',
        'hero_video_badge' => 'YouTube-ready showcase',
        'courses_kicker' => 'Course catalog',
        'courses_title' => 'Featured courses',
        'courses_subtitle' => 'Pick a flagship program, a focused quickstart, or a practical specialty course from one clear catalog.',
        'problem_kicker' => 'Problem to solution',
        'problem_title' => 'Turn a scattered course storefront into a focused buying and learning experience',
        'problem_subtitle' => 'The platform brings your catalog, checkout, curriculum, and instructor credibility into one clear journey.',
        'trust_1_title' => 'Fast, familiar checkout',
        'trust_1_body' => 'Card, PayPal, and manual payment options are presented in one clean flow.',
        'trust_2_title' => 'Instant enrollment access',
        'trust_2_body' => 'The moment enrollment is complete, the next lesson is ready to open.',
        'trust_3_title' => 'Structured student journey',
        'trust_3_body' => 'Lessons stay ordered, progress stays visible, and the platform stays easy to follow.',
        'trust_4_title' => 'Trust before purchase',
        'trust_4_body' => 'A visible instructor, clear pricing, and protected access make the offer feel credible.',
        'flow_kicker' => 'How it works',
        'flow_title' => 'Guide the user from discovery to enrollment to structured learning',
        'flow_subtitle' => 'The buying journey and the learning journey should feel like one connected product, not separate screens.',
        'flow_step_1_title' => 'Discover the offer',
        'flow_step_1_body' => 'A clean landing page and stronger course cards help visitors understand the value quickly.',
        'flow_step_2_title' => 'Enroll with confidence',
        'flow_step_2_body' => 'Pricing, payment choices, and course outcomes stay visible when the user is ready to act.',
        'flow_step_3_title' => 'Start learning fast',
        'flow_step_3_body' => 'Students land in a structured curriculum with visible progress and a clear next lesson.',
        'testimonials_kicker' => 'Testimonials',
        'testimonials_title' => 'What students say after joining Learnova courses',
        'testimonials_subtitle' => 'Short, believable feedback helps visitors trust the instructor and understand the learning experience before they enroll.',
        'footer_kicker' => 'Launch with confidence',
        'footer_title' => 'Present, launch, and sell your courses with a storefront that feels premium from the first click',
        'footer_body' => 'Give your courses a cleaner public presence, guide visitors into checkout with less friction, and help students enter the curriculum with confidence.',
    ];

    public const TESTIMONIAL_DEFAULTS = [
        [
            'name' => 'Sara Mitchell',
            'role' => 'Product designer',
            'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
            'quote' => 'The entire experience feels premium and clear. I knew exactly what I was buying before checkout.',
        ],
        [
            'name' => 'Omar Farouk',
            'role' => 'Independent creator',
            'avatar' => 'https://randomuser.me/api/portraits/men/62.jpg',
            'quote' => 'The course cards are simple to compare, and the purchase flow feels much more trustworthy than most demos.',
        ],
        [
            'name' => 'Lina Hassan',
            'role' => 'Marketing consultant',
            'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
            'quote' => 'The product feels like a real course business, not a generic template. That matters a lot for trust.',
        ],
        [
            'name' => 'Daniel Ross',
            'role' => 'Online educator',
            'avatar' => 'https://randomuser.me/api/portraits/men/33.jpg',
            'quote' => 'The lesson flow is simple to follow, the checkout is clean, and the platform feels ready to sell from day one.',
        ],
        [
            'name' => 'Maya Collins',
            'role' => 'Course strategist',
            'avatar' => 'https://randomuser.me/api/portraits/women/12.jpg',
            'quote' => 'I love how quickly visitors can find the right program and feel confident deciding to enroll.',
        ],
        [
            'name' => 'Noah Kim',
            'role' => 'Startup mentor',
            'avatar' => 'https://randomuser.me/api/portraits/men/45.jpg',
            'quote' => 'This platform makes my course pages look dependable and more likely to convert each month.',
        ],
    ];

    public static function copy(SettingsService $settings): array
    {
        $copy = [];

        foreach (self::COPY_DEFAULTS as $key => $default) {
            $copy[$key] = (string) $settings->get("landing.copy.{$key}", $default);
        }

        return $copy;
    }

    public static function testimonials(SettingsService $settings): array
    {
        return collect(self::TESTIMONIAL_DEFAULTS)
            ->map(function (array $testimonial, int $index) use ($settings): array {
                $number = $index + 1;

                return [
                    'name' => (string) $settings->get("landing.testimonials.{$number}.name", $testimonial['name']),
                    'role' => (string) $settings->get("landing.testimonials.{$number}.role", $testimonial['role']),
                    'avatar' => MediaAsset::url(
                        (string) $settings->get("landing.testimonials.{$number}.avatar", $testimonial['avatar']),
                        MediaAsset::avatarFallbackPath($testimonial['name'])
                    ),
                    'quote' => (string) $settings->get("landing.testimonials.{$number}.quote", $testimonial['quote']),
                ];
            })
            ->all();
    }
}
