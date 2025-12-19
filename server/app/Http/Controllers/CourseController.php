<?php

namespace App\Http\Controllers;

use App\Actions\Courses\ListPublishedCoursesAction;
use App\Actions\Courses\ShowCourseAction;
use App\Actions\Courses\CheckUserEnrollmentAction;
use App\Actions\Courses\EnrollUserInCourseAction;
use App\Models\Course;
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
        Request $request
    ): View
    {
        $course = $action->execute($course);
        $isEnrolled = $checker->execute($request->user(), $course);
        return view('courses.show', compact('course', 'isEnrolled'));
    }

    public function enroll(
        Course $course,
        EnrollUserInCourseAction $enrollAction,
        Request $request
    ): RedirectResponse
    {
        $enrollAction->execute($request->user(), $course);
        return redirect()->route('courses.show', $course)
            ->with('status', 'enrolled');
    }
}
