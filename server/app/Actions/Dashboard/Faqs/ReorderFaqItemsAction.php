<?php

namespace App\Actions\Dashboard\Faqs;

use App\Models\FaqItem;

class ReorderFaqItemsAction
{
    public function execute(array $faqIds): void
    {
        foreach (array_values($faqIds) as $index => $faqId) {
            FaqItem::query()
                ->whereKey($faqId)
                ->update(['sort_order' => ($index + 1) * 10]);
        }
    }
}
