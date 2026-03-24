<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CourseModules\ReorderCourseModulesRequest;
use App\Http\Requests\Dashboard\CourseModules\StoreCourseModuleRequest;
use App\Http\Requests\Dashboard\CourseModules\UpdateCourseModuleRequest;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CourseModuleController extends Controller
{
    public function store(StoreCourseModuleRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $course->modules()->create([
            ...$request->validated(),
            'position' => CourseModule::nextPositionForCourse($course),
        ]);

        return redirect()->route('dashboard.courses.lessons.index', $course)->with('status', 'Module created.');
    }

    public function update(UpdateCourseModuleRequest $request, CourseModule $module): RedirectResponse
    {
        $this->authorize('update', $module->course);

        $module->update($request->validated());

        return redirect()->route('dashboard.courses.lessons.index', $module->course)->with('status', 'Module updated.');
    }

    public function destroy(CourseModule $module): RedirectResponse
    {
        $this->authorize('update', $module->course);

        $course = $module->course;
        $fallbackModule = $course->modules()
            ->whereKeyNot($module->id)
            ->orderBy('position')
            ->first();

        if ($fallbackModule) {
            $module->lessons()->update(['module_id' => $fallbackModule->id]);
        } else {
            $module->lessons()->update(['module_id' => null]);
        }

        $module->delete();
        CourseModule::normalizePositionsForCourse($course);

        return redirect()->route('dashboard.courses.lessons.index', $course)->with('status', 'Module deleted.');
    }

    public function reorder(ReorderCourseModulesRequest $request, Course $course): JsonResponse
    {
        $this->authorize('update', $course);

        $moduleIds = collect($request->validated('module_ids', []))
            ->map(fn ($moduleId) => (int) $moduleId)
            ->filter()
            ->unique()
            ->values()
            ->all();

        CourseModule::reorderForCourse($course, $moduleIds);

        return response()->json([
            'message' => 'Module order saved.',
        ]);
    }
}
