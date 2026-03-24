<?php

namespace App\Actions\Dashboard\Faqs;

use App\Models\FaqItem;

class UpdateFaqItemAction
{
    public function execute(FaqItem $faqItem, array $data): FaqItem
    {
        $faqItem->update([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'is_visible' => (bool) ($data['is_visible'] ?? false),
        ]);

        return $faqItem->refresh();
    }
}
