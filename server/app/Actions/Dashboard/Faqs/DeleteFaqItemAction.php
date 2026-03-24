<?php

namespace App\Actions\Dashboard\Faqs;

use App\Models\FaqItem;

class DeleteFaqItemAction
{
    public function execute(FaqItem $faqItem): void
    {
        $faqItem->delete();
    }
}
