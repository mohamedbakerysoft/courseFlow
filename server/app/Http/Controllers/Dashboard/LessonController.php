<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\Lessons\CreateLessonAction;
use App\Actions\Dashboard\Lessons\DeleteLessonAction;
use App\Actions\Dashboard\Lessons\ListCourseLessonsAction;
use App\Actions\Dashboard\Lessons\UpdateLessonAction;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Course $course, ListCourseLessonsAction $list)
    {
        $this->authorize('view', $course);
        $lessons = $list->execute($course);

        return view('dashboard.lessons.index', compact('course', 'lessons'));
    }

    public function create(Course $course)
    {
        $this->authorize('view', $course);

        return view('dashboard.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course, CreateLessonAction $create)
    {
        $this->authorize('view', $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash'],
            'video_url' => ['required', 'url'],
            'position' => ['required', 'integer', 'min:1'],
        ]);

        $lesson = $create->execute($course, $validated);

        return redirect()->route('dashboard.lessons.edit', $lesson)->with('status', 'Lesson created.');
    }

    public function edit(Lesson $lesson)
    {
        $this->authorize('update', $lesson);

        return view('dashboard.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson, UpdateLessonAction $update)
    {
        $this->authorize('update', $lesson);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash'],
            'video_url' => ['required', 'url'],
            'position' => ['required', 'integer', 'min:1'],
        ]);

        $update->execute($lesson, $validated);

        return back()->with('status', 'Lesson updated.');
    }

    public function destroy(Lesson $lesson, DeleteLessonAction $delete)
    {
        $this->authorize('delete', $lesson);
        $delete->execute($lesson);

        return redirect()->route('dashboard.courses.lessons.index', $lesson->course)->with('status', 'Lesson deleted.');
    }
}
