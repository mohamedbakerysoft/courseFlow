<?php

namespace App\Http\Controllers;

use App\Actions\Courses\CheckUserEnrollmentAction;
use App\Actions\Courses\EnrollUserInCourseAction;
use App\Actions\Courses\ListPublishedCoursesAction;
use App\Actions\Courses\ShowCourseAction;
use App\Actions\Progress\CalculateCourseProgressAction;
use App\Models\Course;
use App\Services\SettingsService;
use App\Support\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
    ): View {
        if (($course->product_type ?? Course::TYPE_COURSE) !== Course::TYPE_COURSE) {
            abort(404);
        }

        $course = $action->execute($course);
        $isEnrolled = $checker->execute($request->user(), $course);
        $progressPercent = $isEnrolled ? $progressAction->execute($request->user(), $course) : 0;
        $lessons = $course->publishedLessonsList();
        $firstLesson = $lessons->first();
        $nextLesson = null;

        $completedLessonIds = [];
        if ($request->user() && $isEnrolled) {
            $completedLessonIds = $request->user()->completedLessons()
                ->where('lessons.course_id', $course->id)
                ->pluck('lessons.id')
                ->toArray();
        }

        if ($isEnrolled) {
            $nextLesson = $lessons->first(fn ($lesson) => ! in_array($lesson->id, $completedLessonIds, true)) ?? $firstLesson;
        }

        $isStripeEnabled = (bool) $settings->get('payments.stripe.enabled', true);
        $isPayPalEnabled = (bool) $settings->get('payments.paypal.enabled', true);
        $manualInstructions = (string) $settings->get('payments.manual.instructions', 'Send the course fee via bank transfer or cash and upload your proof of payment.');
        $hasManualPayment = trim($manualInstructions) !== '';
        $hasAnyPaymentMethod = $isStripeEnabled || $isPayPalEnabled || $hasManualPayment;

        $envOk = app()->environment(['production']);
        $stripeConfigValid = ! $envOk || ((string) config('services.stripe.publishable_key') !== '' && (string) config('services.stripe.secret') !== '');
        $paypalClientIdValue = (string) $settings->get('paypal.client_id', '');
        $paypalSecretValue = (string) $settings->get('paypal.client_secret', '');
        $paypalModeValue = (string) $settings->get('paypal.mode', 'sandbox');
        $paypalClientOk = $paypalClientIdValue !== '' && $paypalSecretValue !== '';
        $paypalModeOk = in_array(strtolower($paypalModeValue), ['sandbox', 'live'], true);
        $paypalConfigValid = ! $envOk || ($paypalClientOk && $paypalModeOk);
        $stripeAvailable = $isStripeEnabled && $stripeConfigValid;
        $paypalAvailable = $isPayPalEnabled && $paypalConfigValid;
        $hasAnyAvailablePaymentMethod = $stripeAvailable || $paypalAvailable || $hasManualPayment;
        $hasSomeUnavailablePaymentMethod = ($isStripeEnabled && ! $stripeAvailable) || ($isPayPalEnabled && ! $paypalAvailable);

        $instructorName = (string) ($settings->get('instructor.name') ?: ($course->instructor?->name ?? ''));
        $instructorBio = (string) ($settings->get('instructor.bio') ?: ($course->instructor?->bio ?? ''));
        $instructorImagePath = (string) $settings->get('landing.instructor_image', '');
        $instructorImageUrl = MediaAsset::url($instructorImagePath, MediaAsset::avatarFallbackPath($instructorName));

        return view('courses.show', compact(
            'course',
            'isEnrolled',
            'progressPercent',
            'lessons',
            'firstLesson',
            'nextLesson',
            'completedLessonIds',
            'stripeAvailable',
            'paypalAvailable',
            'hasManualPayment',
            'hasAnyPaymentMethod',
            'hasAnyAvailablePaymentMethod',
            'hasSomeUnavailablePaymentMethod',
            'instructorName',
            'instructorBio',
            'instructorImageUrl',
            'paypalClientIdValue',
        ));
    }

    public function enroll(
        Course $course,
        EnrollUserInCourseAction $enrollAction,
        Request $request
    ): RedirectResponse {
        if (! ($course->is_free || (float) $course->price == 0.0)) {
            abort(403);
        }
        $enrollAction->execute($request->user(), $course);

        return redirect()->route('courses.show', $course)
            ->with('status', 'enrolled');
    }
}
