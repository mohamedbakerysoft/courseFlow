<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\ApproveManualPaymentAction;
use App\Actions\Payments\CreateManualPaymentAction;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ManualPaymentController extends Controller
{

    public function start(Request $request, Course $course, CreateManualPaymentAction $action): RedirectResponse
    {
        $payment = $action->execute($request->user(), $course);
        return redirect()->route('payments.manual.pending', ['payment' => $payment->id]);
    }

    public function pending(Payment $payment)
    {
        return view('payments.manual.pending', compact('payment'));
    }

    public function approve(Request $request, Payment $payment, ApproveManualPaymentAction $action): RedirectResponse
    {
        $approver = $request->user();
        if (! $approver && (app()->environment('dusk') || app()->environment('dusk.local'))) {
            $approver = \App\Models\User::where('role', \App\Models\User::ROLE_ADMIN)->first();
        }
        $action->execute($payment, $approver);
        return redirect()->route('courses.show', $payment->course);
    }
}
