<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\Lessons\CreateLessonAction;
use App\Actions\Dashboard\Lessons\DeleteLessonAction;
use App\Actions\Dashboard\Lessons\ListCourseLessonsAction;
use App\Actions\Dashboard\Lessons\UpdateLessonAction;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LessonController extends Controller
{
    public function index(Course $course, ListCourseLessonsAction $list)
    {
        $this->authorize('view', $course);
        $lessons = $list->execute($course);
        $modules = $course->modules()->with('lessons')->get();

        return view('dashboard.lessons.index', compact('course', 'lessons', 'modules'));
    }

    public function create(Course $course)
    {
        $this->authorize('view', $course);

        $modules = $course->modules()->orderBy('position')->get();
        $selectedModuleId = request()->integer('module_id');

        return view('dashboard.lessons.create', compact('course', 'modules', 'selectedModuleId'));
    }

    public function store(Request $request, Course $course, CreateLessonAction $create)
    {
        $this->authorize('view', $course);

        $validated = $request->validate([
            'module_id' => ['required', Rule::exists('course_modules', 'id')->where('course_id', $course->id)],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('lessons', 'slug')->where('course_id', $course->id),
            ],
            'video_url' => ['nullable', 'url'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4', 'max:102400'],
        ]);

        if (! $request->filled('video_url') && ! $request->hasFile('video_file')) {
            return back()
                ->withErrors(['video_url' => __('Add a YouTube/video URL or upload an MP4 file.')])
                ->withInput();
        }

        if ($request->filled('video_url') && $request->hasFile('video_file')) {
            return back()
                ->withErrors(['video_url' => __('Choose either a video URL or an MP4 upload, not both.')])
                ->withInput();
        }

        if ($request->hasFile('video_file')) {
            $validated['video_file_path'] = $request->file('video_file')->store('lesson-videos', 'public');
            $validated['video_url'] = null;
        }

        $lesson = $create->execute($course, $validated);

        return redirect()->route('dashboard.lessons.edit', $lesson)->with('status', 'Lesson created.');
    }

    public function edit(Lesson $lesson)
    {
        $this->authorize('update', $lesson);

        $modules = $lesson->course->modules()->orderBy('position')->get();

        return view('dashboard.lessons.edit', compact('lesson', 'modules'));
    }

    public function update(Request $request, Lesson $lesson, UpdateLessonAction $update)
    {
        $this->authorize('update', $lesson);

        $validated = $request->validate([
            'module_id' => ['required', Rule::exists('course_modules', 'id')->where('course_id', $lesson->course_id)],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('lessons', 'slug')
                    ->where('course_id', $lesson->course_id)
                    ->ignore($lesson->id),
            ],
            'video_url' => ['nullable', 'url'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4', 'max:102400'],
        ]);

        if (! $request->filled('video_url') && ! $request->hasFile('video_file') && ! filled($lesson->video_file_path)) {
            return back()
                ->withErrors(['video_url' => __('Add a YouTube/video URL or upload an MP4 file.')])
                ->withInput();
        }

        if ($request->filled('video_url') && $request->hasFile('video_file')) {
            return back()
                ->withErrors(['video_url' => __('Choose either a video URL or an MP4 upload, not both.')])
                ->withInput();
        }

        if ($request->hasFile('video_file')) {
            if (filled($lesson->video_file_path)) {
                Storage::disk('public')->delete($lesson->video_file_path);
            }

            $validated['video_file_path'] = $request->file('video_file')->store('lesson-videos', 'public');
            $validated['video_url'] = null;
        } elseif ($request->filled('video_url') && filled($lesson->video_file_path)) {
            Storage::disk('public')->delete($lesson->video_file_path);
            $validated['video_file_path'] = null;
        }

        $update->execute($lesson, $validated);

        return back()->with('status', 'Lesson updated.');
    }

    public function destroy(Lesson $lesson, DeleteLessonAction $delete)
    {
        $this->authorize('delete', $lesson);
        $delete->execute($lesson);

        return redirect()->route('dashboard.courses.lessons.index', $lesson->course)->with('status', 'Lesson deleted.');
    }

    public function publish(Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson);

        $lesson->update([
            'status' => Lesson::STATUS_PUBLISHED,
        ]);

        return redirect()
            ->route('dashboard.courses.lessons.index', $lesson->course)
            ->with('status', 'Lesson published.');
    }

    public function unpublish(Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson);

        $lesson->update([
            'status' => Lesson::STATUS_DRAFT,
        ]);

        return redirect()
            ->route('dashboard.courses.lessons.index', $lesson->course)
            ->with('status', 'Lesson unpublished.');
    }

    public function reorder(Request $request, Course $course): JsonResponse
    {
        $this->authorize('update', $course);

        $modules = collect($request->input('modules', []))
            ->filter(fn ($entry) => is_array($entry))
            ->map(function (array $entry) use ($course) {
                $moduleId = isset($entry['module_id']) ? (int) $entry['module_id'] : null;
                $validModuleId = $course->modules()->whereKey($moduleId)->exists() ? $moduleId : null;

                return [
                    'module_id' => $validModuleId,
                    'lesson_ids' => collect($entry['lesson_ids'] ?? [])
                        ->map(fn ($lessonId) => (int) $lessonId)
                        ->filter()
                        ->unique()
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        Lesson::reorderForCourse($course, $modules);

        return response()->json([
            'message' => 'Lesson order saved.',
        ]);
    }
}
