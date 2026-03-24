<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\Faqs\CreateFaqItemAction;
use App\Actions\Dashboard\Faqs\DeleteFaqItemAction;
use App\Actions\Dashboard\Faqs\ReorderFaqItemsAction;
use App\Actions\Dashboard\Faqs\UpdateFaqItemAction;
use App\Actions\Dashboard\Faqs\UpdateFaqPageSettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Faqs\ReorderFaqItemsRequest;
use App\Http\Requests\Dashboard\Faqs\StoreFaqItemRequest;
use App\Http\Requests\Dashboard\Faqs\UpdateFaqItemRequest;
use App\Http\Requests\Dashboard\Faqs\UpdateFaqPageSettingsRequest;
use App\Models\FaqItem;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(SettingsService $settings): View
    {
        return view('dashboard.faqs.index', [
            'faqItems' => FaqItem::adminList(),
            'faqHeading' => (string) $settings->get('faq.page.heading', 'Frequently Asked Questions'),
            'faqSubheading' => (string) $settings->get('faq.page.subheading', 'Find quick answers about courses, payments, enrollment, and how the storefront experience works.'),
        ]);
    }

    public function store(StoreFaqItemRequest $request, CreateFaqItemAction $action): RedirectResponse
    {
        $action->execute($request->validated());

        return back()->with('status', 'FAQ item created.');
    }

    public function update(UpdateFaqItemRequest $request, FaqItem $faq, UpdateFaqItemAction $action): RedirectResponse
    {
        $action->execute($faq, $request->validated());

        return back()->with('status', 'FAQ item updated.');
    }

    public function destroy(FaqItem $faq, DeleteFaqItemAction $action): RedirectResponse
    {
        $action->execute($faq);

        return back()->with('status', 'FAQ item deleted.');
    }

    public function reorder(ReorderFaqItemsRequest $request, ReorderFaqItemsAction $action): JsonResponse
    {
        $action->execute($request->validated('faq_ids'));

        return response()->json(['status' => 'ok']);
    }

    public function updatePage(UpdateFaqPageSettingsRequest $request, UpdateFaqPageSettingsAction $action): RedirectResponse
    {
        $action->execute($request->validated());

        return back()->with('status', 'FAQ page settings updated.');
    }
}
