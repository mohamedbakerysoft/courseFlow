<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\ApproveManualPaymentAction;
use App\Actions\Payments\CreateManualPaymentAction;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Services\SettingsService;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ManualPaymentController extends Controller
{
    public function start(Request $request, Course $course, CreateManualPaymentAction $action): RedirectResponse
    {
        $payment = $action->execute($request->user(), $course);

        return redirect()->route('payments.manual.pending', ['payment' => $payment->id]);
    }

    public function pending(Payment $payment, SettingsService $settings)
    {
        abort_unless(auth()->id() === $payment->user_id, Response::HTTP_FORBIDDEN);

        $manualInstructions = (string) $settings->get('payments.manual.instructions', '');

        return view('payments.manual.pending', compact('payment', 'manualInstructions'));
    }

    public function submit(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless(auth()->id() === $payment->user_id, Response::HTTP_FORBIDDEN);

        $validated = $request->validate([
            'payment_reference' => ['required', 'string', 'max:2000'],
            'proof_image' => ['required', 'image', 'max:6144'],
        ]);

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->withErrors([
                'payment_reference' => __('This manual payment request has already been reviewed.'),
            ]);
        }

        if (filled($payment->proof_path)) {
            Storage::disk('public')->delete(str_replace('storage/', '', $payment->proof_path));
        }

        $proofPath = $request->file('proof_image')->store('manual-payments', 'public');

        $payment->update([
            'payment_reference' => $validated['payment_reference'],
            'proof_path' => $proofPath,
            'submitted_at' => Carbon::now(),
            'review_notes' => null,
            'rejected_at' => null,
        ]);

        return back()->with('status', 'manual-payment-submitted');
    }

    public function approve(Request $request, Payment $payment, ApproveManualPaymentAction $action): RedirectResponse
    {
        if (app()->environment('production')) {
            $this->authorize('approve', $payment);
        }
        $approver = $request->user();
        if (! $approver && ! app()->environment('production')) {
            $approver = \App\Models\User::where('role', \App\Models\User::ROLE_ADMIN)->first();
        }
        $action->execute($payment, $approver);

        return redirect()->route(
            ($payment->course?->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK ? 'books.show' : 'courses.show',
            $payment->course
        );
    }

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        if (app()->environment('production')) {
            $this->authorize('reject', $payment);
        }

        $validated = $request->validate([
            'review_notes' => ['required', 'string', 'max:2000'],
        ]);

        Payment::query()
            ->where('user_id', $payment->user_id)
            ->where('course_id', $payment->course_id)
            ->where('status', Payment::STATUS_FAILED)
            ->whereKeyNot($payment->id)
            ->delete();

        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'approved_by' => $request->user()?->id,
            'review_notes' => $validated['review_notes'],
            'rejected_at' => Carbon::now(),
        ]);

        return redirect()->route('dashboard.finance.index')->with('status', 'Manual payment rejected.');
    }
}
