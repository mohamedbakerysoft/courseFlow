<?php

namespace App\Http\Controllers;

use App\Actions\Courses\ShowLessonAction;
use App\Actions\Progress\CalculateCourseProgressAction;
use App\Actions\Progress\MarkLessonCompletedAction;
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
    ): View {
        $lesson = $action->execute($course, $lesson);
        $isCompleted = $markAction->execute($request->user(), $lesson);
        $progressPercent = $progressAction->execute($request->user(), $course);
        $prevLesson = $course->lessons()->published()
            ->where('position', '<', $lesson->position)
            ->orderBy('position', 'desc')
            ->select(['id', 'slug', 'title', 'position'])
            ->first();
        $nextLesson = $course->lessons()->published()
            ->where('position', '>', $lesson->position)
            ->orderBy('position', 'asc')
            ->select(['id', 'slug', 'title', 'position'])
            ->first();

        return view('lessons.show', compact('course', 'lesson', 'isCompleted', 'progressPercent', 'prevLesson', 'nextLesson'));
    }
}
