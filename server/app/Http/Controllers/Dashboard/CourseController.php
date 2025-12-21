<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\Courses\CreateCourseAction;
use App\Actions\Dashboard\Courses\DeleteCourseAction;
use App\Actions\Dashboard\Courses\ListInstructorCoursesAction;
use App\Actions\Dashboard\Courses\PublishCourseAction;
use App\Actions\Dashboard\Courses\UnpublishCourseAction;
use App\Actions\Dashboard\Courses\UpdateCourseAction;
use App\Actions\Dashboard\Courses\UploadCourseThumbnailAction;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request, ListInstructorCoursesAction $list)
    {
        $courses = $list->execute($request->user(), 20);
        return view('dashboard.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('dashboard.courses.create');
    }

    public function store(Request $request, CreateCourseAction $create, UploadCourseThumbnailAction $upload)
    {
        $this->authorize('create', Course::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:courses,slug'],
            'description' => ['nullable', 'string'],
            'thumbnail_path' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
            'is_free' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', 'max:12'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $upload->execute($request->file('thumbnail'));
        }

        $course = $create->execute($request->user(), $validated);

        return redirect()->route('dashboard.courses.edit', $course)->with('status', 'Course created.');
    }

    public function edit(Course $course)
    {
        $this->authorize('view', $course);
        return view('dashboard.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course, UpdateCourseAction $update, UploadCourseThumbnailAction $upload)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:courses,slug,'.$course->id],
            'description' => ['nullable', 'string'],
            'thumbnail_path' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
            'is_free' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', 'max:12'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $upload->execute($request->file('thumbnail'));
        }

        $update->execute($course, $validated);

        return back()->with('status', 'Course updated.');
    }

    public function destroy(Course $course, DeleteCourseAction $delete)
    {
        $this->authorize('delete', $course);
        $delete->execute($course);
        return redirect()->route('dashboard.courses.index')->with('status', 'Course deleted.');
    }

    public function publish(Course $course, PublishCourseAction $publish)
    {
        $this->authorize('publish', $course);
        $publish->execute($course);
        return back()->with('status', 'Course published.');
    }

    public function unpublish(Course $course, UnpublishCourseAction $unpublish)
    {
        $this->authorize('publish', $course);
        $unpublish->execute($course);
        return back()->with('status', 'Course unpublished.');
    }
}
