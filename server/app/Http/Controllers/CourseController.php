<?php

namespace App\Http\Controllers;

use App\Actions\Courses\ListPublishedCoursesAction;
use App\Actions\Courses\ShowCourseAction;
use App\Actions\Courses\CheckUserEnrollmentAction;
use App\Actions\Courses\EnrollUserInCourseAction;
use App\Actions\Progress\CalculateCourseProgressAction;
use App\Models\Course;
use App\Services\SettingsService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    public function index(ListPublishedCoursesAction $action): View
    {
        $courses = $action->execute();
        return view('courses.index', compact('courses'));
    }

    public function show(
        Course $course,
        ShowCourseAction $action,
        CheckUserEnrollmentAction $checker,
        Request $request,
        CalculateCourseProgressAction $progressAction,
        SettingsService $settings
    ): View
    {
        $course = $action->execute($course);
        $isEnrolled = $checker->execute($request->user(), $course);
        $progressPercent = $isEnrolled ? $progressAction->execute($request->user(), $course) : 0;
        $lessons = $course->lessons()->published()->select(['id', 'slug', 'title', 'position'])->orderBy('position')->get();
        $firstLesson = $lessons->first();
        $isStripeEnabled = (bool) $settings->get('payments.stripe.enabled', true);
        $isPayPalEnabled = (bool) $settings->get('payments.paypal.enabled', true);
        $manualInstructions = (string) $settings->get('payments.manual.instructions', 'Send the course fee via bank transfer or cash and upload your proof of payment.');
        $hasManualPayment = trim($manualInstructions) !== '';
        $hasAnyPaymentMethod = $isStripeEnabled || $isPayPalEnabled || $hasManualPayment;

        return view('courses.show', compact(
            'course',
            'isEnrolled',
            'progressPercent',
            'lessons',
            'firstLesson',
            'isStripeEnabled',
            'isPayPalEnabled',
            'hasManualPayment',
            'hasAnyPaymentMethod',
        ));
    }

    public function enroll(
        Course $course,
        EnrollUserInCourseAction $enrollAction,
        Request $request
    ): RedirectResponse
    {
        if (! ($course->is_free || (float) $course->price == 0.0)) {
            abort(403);
        }
        $enrollAction->execute($request->user(), $course);
        return redirect()->route('courses.show', $course)
            ->with('status', 'enrolled');
    }
}
