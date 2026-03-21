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
use App\Models\ReferenceOption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request, ListInstructorCoursesAction $list)
    {
        $courses = $list->executeByType($request->user(), Course::TYPE_COURSE);

        return view('dashboard.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('dashboard.courses.create', $this->formOptions());
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
            'currency' => ['nullable', 'string', 'max:8', Rule::exists('reference_options', 'code')->where('type', ReferenceOption::TYPE_CURRENCY)->where('is_active', true)],
            'is_free' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', 'max:12', Rule::exists('reference_options', 'code')->where('type', ReferenceOption::TYPE_LANGUAGE)->where('is_active', true)],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $upload->execute($request->file('thumbnail'));
        }

        $validated['product_type'] = Course::TYPE_COURSE;

        $course = $create->execute($request->user(), $validated);

        return redirect()->route('dashboard.courses.edit', $course)->with('status', 'Course created.');
    }

    public function edit(Course $course)
    {
        $this->authorize('view', $course);

        return view('dashboard.courses.edit', array_merge(['course' => $course], $this->formOptions()));
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
            'currency' => ['nullable', 'string', 'max:8', Rule::exists('reference_options', 'code')->where('type', ReferenceOption::TYPE_CURRENCY)->where('is_active', true)],
            'is_free' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', 'max:12', Rule::exists('reference_options', 'code')->where('type', ReferenceOption::TYPE_LANGUAGE)->where('is_active', true)],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $upload->execute($request->file('thumbnail'));
        }

        $validated['product_type'] = Course::TYPE_COURSE;

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

    private function formOptions(): array
    {
        return [
            'languageOptions' => ReferenceOption::languageOptions(),
            'currencyOptions' => ReferenceOption::currencyOptions(),
        ];
    }
}
