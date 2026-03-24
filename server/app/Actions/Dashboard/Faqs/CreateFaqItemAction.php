<?php

namespace App\Actions\Dashboard\Faqs;

use App\Models\FaqItem;

class CreateFaqItemAction
{
    public function execute(array $data): FaqItem
    {
        $nextSortOrder = ((int) FaqItem::query()->max('sort_order')) + 10;

        return FaqItem::create([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'is_visible' => (bool) ($data['is_visible'] ?? false),
            'sort_order' => $nextSortOrder,
        ]);
    }
}
