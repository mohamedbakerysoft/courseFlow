<?php

namespace App\Support;

use App\Services\SettingsService;

class LandingContent
{
    public const COPY_DEFAULTS = [
        'hero_kicker' => 'Independent course business platform',
        'hero_highlight_1' => 'One-time pricing and fast checkout',
        'hero_highlight_2' => 'Instant lesson access after enrollment',
        'hero_highlight_3' => 'Protected curriculum with saved progress',
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
        'instructor_kicker' => 'Instructor credibility',
        'instructor_card_1_title' => 'Premium presentation',
        'instructor_card_1_body' => 'A cleaner public identity improves the first impression and supports conversion.',
        'instructor_card_2_title' => 'Direct call to action',
        'instructor_card_2_body' => 'Students can move from discovery to enrollment without dead ends or clutter.',
        'instructor_card_3_title' => 'Consistent experience',
        'instructor_card_3_body' => 'The same visual system carries from landing page to course page to dashboard.',
        'testimonials_kicker' => 'Testimonials',
        'testimonials_title' => 'What users notice when the storefront finally feels premium',
        'testimonials_subtitle' => 'These signals matter because strong visual clarity improves trust before users commit to payment or enrollment.',
        'faq_kicker' => 'Frequently asked questions',
        'faq_title' => 'Remove friction before users reach the buy decision',
        'faq_subtitle' => 'A premium course product answers practical questions early, keeps pricing clear, and makes the next step obvious.',
        'contact_kicker' => 'Get in touch',
        'contact_title' => 'Ask a question before you enroll',
        'contact_subtitle' => 'Use this section for support, custom requests, or questions about which course to start with.',
        'footer_kicker' => 'Your next step',
        'footer_title' => 'Present your courses like a premium product and make the next action obvious',
        'footer_body' => 'Clear messaging, stronger hierarchy, and a simpler CTA structure help this platform feel much closer to a sellable product.',
    ];

    public const TESTIMONIAL_DEFAULTS = [
        [
            'name' => 'Sara Mitchell',
            'role' => 'Product designer',
            'quote' => 'The entire experience feels premium and clear. I knew exactly what I was buying before checkout.',
        ],
        [
            'name' => 'Omar Farouk',
            'role' => 'Independent creator',
            'quote' => 'The course cards are simple to compare, and the purchase flow feels much more trustworthy than most demos.',
        ],
        [
            'name' => 'Lina Hassan',
            'role' => 'Marketing consultant',
            'quote' => 'The product feels like a real course business, not a generic template. That matters a lot for trust.',
        ],
    ];

    public const FAQ_DEFAULTS = [
        [
            'question' => 'How do students access lessons after they enroll?',
            'answer' => 'Each course unlocks its structured lessons immediately after free enrollment or payment approval, so the first next step is always clear.',
        ],
        [
            'question' => 'What payment methods are supported?',
            'answer' => 'Learnova supports Stripe, PayPal, and manual payment flows so instructors can sell in the way that fits their business.',
        ],
        [
            'question' => 'Is this experience suitable for a solo instructor?',
            'answer' => 'Yes. The storefront, checkout flow, course pages, and instructor profile are designed to help one creator present courses clearly and professionally.',
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
                    'quote' => (string) $settings->get("landing.testimonials.{$number}.quote", $testimonial['quote']),
                ];
            })
            ->all();
    }

    public static function faqs(SettingsService $settings): array
    {
        return collect(self::FAQ_DEFAULTS)
            ->map(function (array $faq, int $index) use ($settings): array {
                $number = $index + 1;

                return [
                    'question' => (string) $settings->get("landing.faqs.{$number}.question", $faq['question']),
                    'answer' => (string) $settings->get("landing.faqs.{$number}.answer", $faq['answer']),
                ];
            })
            ->all();
    }
}
