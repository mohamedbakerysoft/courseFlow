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
use App\Http\Requests\Dashboard\Courses\StoreCourseRequest;
use App\Http\Requests\Dashboard\Courses\UpdateCourseRequest;
use App\Models\Course;
use App\Models\ReferenceOption;
use Illuminate\Http\Request;

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

    public function store(StoreCourseRequest $request, CreateCourseAction $create, UploadCourseThumbnailAction $upload)
    {
        $this->authorize('create', Course::class);

        $validated = $request->validated();

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

    public function update(UpdateCourseRequest $request, Course $course, UpdateCourseAction $update, UploadCourseThumbnailAction $upload)
    {
        $this->authorize('update', $course);

        $validated = $request->validated();

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
