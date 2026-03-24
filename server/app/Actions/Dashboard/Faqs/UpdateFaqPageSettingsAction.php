<?php

namespace App\Actions\Dashboard\Faqs;

use App\Services\SettingsService;

class UpdateFaqPageSettingsAction
{
    public function __construct(
        protected SettingsService $settings,
    ) {}

    public function execute(array $data): void
    {
        $this->settings->set([
            'faq.page.heading' => $data['heading'],
            'faq.page.subheading' => $data['subheading'] ?? '',
        ]);
    }
}
