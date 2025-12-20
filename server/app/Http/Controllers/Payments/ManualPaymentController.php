<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\ApproveManualPaymentAction;
use App\Actions\Payments\CreateManualPaymentAction;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ManualPaymentController extends Controller
{

    public function start(Request $request, Course $course, CreateManualPaymentAction $action): RedirectResponse
    {
        $payment = $action->execute($request->user(), $course);
        return redirect()->route('payments.manual.pending', ['payment' => $payment->id]);
    }

    public function pending(Payment $payment, SettingsService $settings)
    {
        $manualInstructions = (string) $settings->get('payments.manual.instructions', '');
        return view('payments.manual.pending', compact('payment', 'manualInstructions'));
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
        return redirect()->route('courses.show', $payment->course);
    }
}
