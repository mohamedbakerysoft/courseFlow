<?php

namespace App\Http\Controllers;

use App\Actions\Courses\ShowLessonAction;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson, ShowLessonAction $action): View
    {
        $lesson = $action->execute($course, $lesson);
        return view('lessons.show', compact('course', 'lesson'));
    }
}
