<?php

namespace App\Actions\Faqs;

use App\Models\FaqItem;
use App\Services\SettingsService;

class ShowFaqPageAction
{
    public function __construct(
        protected SettingsService $settings,
    ) {}

    public function execute(): array
    {
        return [
            'heading' => (string) $this->settings->get('faq.page.heading', 'Frequently Asked Questions'),
            'subheading' => (string) $this->settings->get('faq.page.subheading', 'Find quick answers about courses, payments, enrollment, and how the storefront experience works.'),
            'faqs' => FaqItem::publicList(),
        ];
    }
}
