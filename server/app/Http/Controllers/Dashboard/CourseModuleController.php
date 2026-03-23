<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseModuleController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $course->modules()->create([
            ...$validated,
            'position' => CourseModule::nextPositionForCourse($course),
        ]);

        return redirect()->route('dashboard.courses.lessons.index', $course)->with('status', 'Module created.');
    }

    public function update(Request $request, CourseModule $module): RedirectResponse
    {
        $this->authorize('update', $module->course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $module->update($validated);

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

    public function reorder(Request $request, Course $course): JsonResponse
    {
        $this->authorize('update', $course);

        $moduleIds = collect($request->input('module_ids', []))
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
