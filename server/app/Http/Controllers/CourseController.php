<?php

namespace App\Http\Controllers;

use App\Actions\Courses\ListPublishedCoursesAction;
use App\Actions\Courses\ShowCourseAction;
use App\Models\Course;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(ListPublishedCoursesAction $action): View
    {
        $courses = $action->execute();
        return view('courses.index', compact('courses'));
    }

    public function show(Course $course, ShowCourseAction $action): View
    {
        $course = $action->execute($course);
        return view('courses.show', compact('course'));
    }
}
