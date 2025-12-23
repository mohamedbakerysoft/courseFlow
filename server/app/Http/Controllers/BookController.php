<?php

namespace App\Http\Controllers;

use App\Actions\Courses\CheckUserEnrollmentAction;
use App\Models\Course;
use App\Services\SettingsService;
use App\Support\MediaAsset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Course::query()
            ->published()
            ->books()
            ->with('instructor')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('books.index', compact('books'));
    }

    public function show(
        Course $book,
        CheckUserEnrollmentAction $checker,
        Request $request,
        SettingsService $settings
    ): View {
        abort_unless(($book->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK, 404);
        abort_unless($book->status === Course::STATUS_PUBLISHED, 404);

        $isEnrolled = $checker->execute($request->user(), $book);
        $displayPrice = $book->is_free || (float) $book->price == 0.0
            ? __('Free')
            : number_format((float) $book->price, 2).' '.strtoupper($book->currency ?: 'USD');

        $isStripeEnabled = (bool) $settings->get('payments.stripe.enabled', true);
        $isPayPalEnabled = (bool) $settings->get('payments.paypal.enabled', true);
        $manualInstructions = (string) $settings->get('payments.manual.instructions', 'Send the course fee via bank transfer or cash and upload your proof of payment.');
        $hasManualPayment = trim($manualInstructions) !== '';
        $hasAnyPaymentMethod = $isStripeEnabled || $isPayPalEnabled || $hasManualPayment;

        $instructorName = (string) ($settings->get('instructor.name') ?: ($book->instructor?->name ?? ''));
        $instructorBio = (string) ($settings->get('instructor.bio') ?: ($book->instructor?->bio ?? ''));
        $instructorImagePath = (string) $settings->get('landing.instructor_image', '');
        $instructorImageUrl = MediaAsset::url($instructorImagePath, MediaAsset::avatarFallbackPath($instructorName));

        return view('books.show', compact(
            'book',
            'isEnrolled',
            'displayPrice',
            'isStripeEnabled',
            'isPayPalEnabled',
            'hasManualPayment',
            'hasAnyPaymentMethod',
            'manualInstructions',
            'instructorName',
            'instructorBio',
            'instructorImageUrl',
        ));
    }

    public function download(Course $book, CheckUserEnrollmentAction $checker, Request $request): StreamedResponse|RedirectResponse
    {
        abort_unless(($book->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK, 404);
        abort_unless($book->status === Course::STATUS_PUBLISHED, 404);

        if (! $book->is_free && (float) $book->price > 0.0) {
            if (! $request->user()) {
                return redirect()->route('login');
            }

            if (! $checker->execute($request->user(), $book)) {
                return redirect()
                    ->route('books.show', $book)
                    ->with('status', __('Complete enrollment before downloading this book.'));
            }
        }

        abort_if(blank($book->download_file_path), 404);
        abort_unless(Storage::disk('public')->exists($book->download_file_path), 404);

        return Storage::disk('public')->download(
            $book->download_file_path,
            $book->slug.'.'.pathinfo($book->download_file_path, PATHINFO_EXTENSION)
        );
    }
}
