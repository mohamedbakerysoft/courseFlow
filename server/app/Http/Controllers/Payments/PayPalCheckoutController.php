<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\CreatePayPalCheckoutAction;
use App\Actions\Payments\CapturePayPalOrderAction;
use App\Actions\Payments\HandlePayPalPaymentSuccessAction;
use App\Actions\Payments\MarkPaymentFailedAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\CreatePayPalOrderRequest;
use App\Http\Requests\Payments\CapturePayPalOrderRequest;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PayPalCheckoutController extends Controller
{
    public function createOrder(CreatePayPalOrderRequest $request, CreatePayPalCheckoutAction $action): Response
    {
        $course = \App\Models\Course::findOrFail((int) $request->input('course_id'));
        $order = $action->execute($request->user(), $course);

        return response(['order_id' => $order['id']], 200);
    }

    public function capture(CapturePayPalOrderRequest $request, CapturePayPalOrderAction $action): Response
    {
        $orderId = (string) $request->input('order_id');
        $action->execute($orderId);

        return response(['ok' => true], 200);
    }

    public function checkout(Request $request, Course $course, CreatePayPalCheckoutAction $action): RedirectResponse
    {
        $order = $action->execute($request->user(), $course);

        return redirect()->away($order['approve_url']);
    }

    public function success(Request $request, HandlePayPalPaymentSuccessAction $handler): RedirectResponse
    {
        $orderId = (string) $request->query('order_id', '');
        $ts = (string) $request->query('t', '');
        $sig = (string) $request->query('sig', '');
        $handler->execute($orderId, $ts, $sig);
        $payment = Payment::where('external_reference', $orderId)->first();
        if (! $payment) {
            return redirect()->route('courses.index');
        }

        return redirect()->route('courses.show', $payment->course);
    }

    public function cancel(Request $request, Course $course, MarkPaymentFailedAction $fail): RedirectResponse
    {
        $payment = Payment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->where('provider', 'paypal')
            ->where('status', \App\Models\Payment::STATUS_PENDING)
            ->latest()->first();
        if ($payment) {
            $fail->execute($payment);
        }

        return redirect()->route('courses.show', $course);
    }
}
