<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\ApproveManualPaymentAction;
use App\Actions\Payments\CreateManualPaymentAction;
use App\Actions\Payments\RejectManualPaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\RejectManualPaymentRequest;
use App\Http\Requests\Payments\SubmitManualPaymentRequest;
use App\Models\Payment;
use App\Models\User;
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

    public function submit(SubmitManualPaymentRequest $request, Payment $payment): RedirectResponse
    {
        abort_unless(auth()->id() === $payment->user_id, Response::HTTP_FORBIDDEN);

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->withErrors([
                'payment_reference' => __('This manual payment request has already been reviewed.'),
            ]);
        }

        if (filled($payment->proof_path)) {
            Storage::disk('public')->delete(str_replace('storage/', '', $payment->proof_path));
        }

        $proofPath = $request->file('proof_image')->store('manual-payments', 'public');
        $validated = $request->validated();

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
            $approver = User::primaryInstructor();
        }
        $result = $action->execute($payment, $approver);

        $status = ($result['result'] ?? null) === 'already_paid_reconciled'
            ? __('Access was restored from an existing completed payment, and the duplicate manual request was closed.')
            : __('Manual payment approved and access granted.');

        return redirect()->route('dashboard.finance.manual_payments')->with('status', $status);
    }

    public function reject(RejectManualPaymentRequest $request, Payment $payment, RejectManualPaymentAction $action): RedirectResponse
    {
        if (app()->environment('production')) {
            $this->authorize('reject', $payment);
        }

        $action->execute($payment, $request->user(), $request->validated('review_notes'));

        return redirect()->route('dashboard.finance.index')->with('status', 'Manual payment rejected.');
    }
}
