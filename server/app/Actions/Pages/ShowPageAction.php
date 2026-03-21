<?php

namespace App\Actions\Pages;

use App\Models\Page;
use App\Services\SettingsService;

class ShowPageAction
{
    public function __construct(
        protected SettingsService $settings
    ) {}

    public function execute(string $slug): Page
    {
        if (in_array($slug, ['terms', 'privacy'], true)) {
            $localeKey = 'en';
            $key = "legal.$slug.$localeKey";
            $content = (string) $this->settings->get($key, '');
            if (trim($content) === '') {
                $content = $this->defaultLegalContent($slug, $localeKey);
            }
            $title = $slug === 'terms'
                ? 'Terms of Service'
                : 'Privacy Policy';
            $page = new Page;
            $page->fill([
                'slug' => $slug,
                'title' => $title,
                'content' => $content,
            ]);

            return $page;
        }

        return Page::findBySlugOrFail($slug);
    }

    protected function defaultLegalContent(string $slug, string $locale): string
    {
        if ($slug === 'terms') {
            return "1. Introduction\nBy using this site, you agree to the following terms of service.\n\n2. User accounts\nYou are responsible for your login credentials and for proper use of your account.\n\n3. Payments & refunds\nAccess to courses is granted after successful payment. A refund policy may be available as stated on the course page.\n\n4. Intellectual property\nAll learning materials are licensed for personal use only. Redistribution or sharing without permission is prohibited.\n\n5. Limitation of liability\nContent is provided as-is. We are not liable for indirect or consequential losses arising from use.\n\n6. Contact info\nFor questions, please use the contact form on the site.";
        }

        return "1. Data we collect\nWe collect basic account details, payment data when required, and usage data to improve the service.\n\n2. How we use data\nWe use data to provide the service, improve the experience, and enhance security, in compliance with applicable laws (e.g., GDPR where applicable).\n\n3. Cookies\nWe use cookies to remember preferences and analyze usage. You can disable cookies in your browser settings.\n\n4. Third‑party services (Stripe, PayPal, Google)\nWe use payment providers, analytics, and possibly video hosting. Your data is subject to their policies.\n\n5. User rights\nYou may request access, correction, or deletion of your data, subject to applicable law.";
    }
}
