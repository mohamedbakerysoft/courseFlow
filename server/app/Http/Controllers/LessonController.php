<?php

namespace App\Http\Controllers;

use App\Actions\Courses\CheckUserEnrollmentAction;
use App\Actions\Courses\ShowLessonAction;
use App\Actions\Progress\CalculateCourseProgressAction;
use App\Actions\Progress\MarkLessonCompletedAction;
use App\Models\Course;
use App\Models\CourseModule;
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

        $completedLessonIds = collect();
        if ($request->user()) {
            $completedLessonIds = $request->user()->completedLessons()
                ->where('lessons.course_id', $course->id)
                ->pluck('lessons.id');
        }

        $isCompleted = $completedLessonIds->contains($lesson->id);

        $progressPercent = $progressAction->execute($request->user(), $course);
        $courseLessons = $course->lessons()
            ->published()
            ->with('module')
            ->orderBy('position')
            ->select(['id', 'course_id', 'module_id', 'slug', 'title', 'position'])
            ->get();

        $firstIncompleteLesson = $courseLessons->first(fn (Lesson $courseLesson) => ! $completedLessonIds->contains($courseLesson->id));
        $firstIncompletePosition = $firstIncompleteLesson?->position;

        $lessonItems = $courseLessons->map(function (Lesson $courseLesson) use ($completedLessonIds, $lesson, $firstIncompletePosition) {
            $completed = $completedLessonIds->contains($courseLesson->id);
            $current = $courseLesson->id === $lesson->id;
            $locked = ! $completed && ! $current && $firstIncompletePosition !== null && $courseLesson->position > $firstIncompletePosition;

            return [
                'lesson' => $courseLesson,
                'is_current' => $current,
                'is_completed' => $completed,
                'is_locked' => $locked,
            ];
        })->values();

        $lessonModules = $course->modules()
            ->with(['lessons' => function ($query) {
                $query->published()
                    ->orderBy('position')
                    ->select(['id', 'course_id', 'module_id', 'slug', 'title', 'position']);
            }])
            ->get()
            ->map(function (CourseModule $module) use ($lessonItems) {
                $moduleLessonItems = $lessonItems
                    ->filter(fn (array $item) => (int) $item['lesson']->module_id === (int) $module->id)
                    ->values();

                $module->lesson_items = $moduleLessonItems;

                return $module;
            })
            ->filter(fn (CourseModule $module) => $module->lesson_items->isNotEmpty())
            ->values();

        $unassignedLessonItems = $lessonItems
            ->filter(fn (array $item) => blank($item['lesson']->module_id))
            ->values();

        if ($unassignedLessonItems->isNotEmpty()) {
            $fallbackModule = new CourseModule([
                'title' => __('Additional lessons'),
                'description' => null,
                'position' => $lessonModules->count() + 1,
            ]);

            $fallbackModule->lesson_items = $unassignedLessonItems;
            $lessonModules->push($fallbackModule);
        }

        return view('lessons.show', compact('course', 'lesson', 'isCompleted', 'progressPercent', 'lessonItems', 'lessonModules'));
    }

    public function complete(
        Course $course,
        Lesson $lesson,
        CheckUserEnrollmentAction $checker,
        MarkLessonCompletedAction $markAction,
        Request $request
    ): \Illuminate\Http\JsonResponse {
        if (! $checker->execute($request->user(), $course)) {
            abort(403);
        }

        $marked = $markAction->execute($request->user(), $lesson);

        return response()->json(['completed' => $marked]);
    }
}
