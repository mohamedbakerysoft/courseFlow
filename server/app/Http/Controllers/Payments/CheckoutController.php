<?php

namespace App\Http\Controllers\Payments;

use App\Actions\Payments\CreateStripeCheckoutSessionAction;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['checkout', 'success', 'cancel']);
    }

    public function checkout(Request $request, Course $course, CreateStripeCheckoutSessionAction $action): RedirectResponse
    {
        if ($course->is_free || (float) $course->price <= 0.0) {
            return redirect()->route('courses.show', $course);
        }

        $session = $action->execute($request->user(), $course);
        return redirect($session['url']);
    }

    public function success(Request $request)
    {
        return view('payments.success');
    }

    public function cancel(Request $request, Course $course)
    {
        return view('payments.cancel', compact('course'));
    }
}
