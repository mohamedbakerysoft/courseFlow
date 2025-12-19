<?php

namespace App\Http\Controllers;

use App\Actions\Courses\ShowLessonAction;
use App\Actions\Progress\MarkLessonCompletedAction;
use App\Actions\Progress\CalculateCourseProgressAction;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(
        Course $course,
        Lesson $lesson,
        ShowLessonAction $action,
        MarkLessonCompletedAction $markAction,
        CalculateCourseProgressAction $progressAction,
        Request $request
    ): View
    {
        $lesson = $action->execute($course, $lesson);
        $isCompleted = $markAction->execute($request->user(), $lesson);
        $progressPercent = $progressAction->execute($request->user(), $course);
        return view('lessons.show', compact('course', 'lesson', 'isCompleted', 'progressPercent'));
    }
}
