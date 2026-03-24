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
use App\Http\Requests\Dashboard\Books\StoreBookRequest;
use App\Http\Requests\Dashboard\Books\UpdateBookRequest;
use App\Models\Course;
use App\Models\ReferenceOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request, ListInstructorCoursesAction $list): View
    {
        $books = $list->executeByType($request->user(), Course::TYPE_BOOK);

        return view('dashboard.books.index', compact('books'));
    }

    public function create(): View
    {
        return view('dashboard.books.create', $this->formOptions());
    }

    public function store(StoreBookRequest $request, CreateCourseAction $create, UploadCourseThumbnailAction $upload): RedirectResponse
    {
        $this->authorize('create', Course::class);

        $validated = $request->validated();
        $validated['product_type'] = Course::TYPE_BOOK;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $upload->execute($request->file('thumbnail'));
        }

        if ($request->hasFile('download_file')) {
            $validated['download_file_path'] = $request->file('download_file')->store('books', 'public');
        }

        $book = $create->execute($request->user(), $validated);

        return redirect()->route('dashboard.books.edit', $book)->with('status', 'Book created.');
    }

    public function edit(Course $book): View
    {
        abort_unless(($book->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK, 404);
        $this->authorize('view', $book);

        return view('dashboard.books.edit', array_merge(['book' => $book], $this->formOptions()));
    }

    public function update(UpdateBookRequest $request, Course $book, UpdateCourseAction $update, UploadCourseThumbnailAction $upload): RedirectResponse
    {
        abort_unless(($book->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK, 404);
        $this->authorize('update', $book);

        $validated = $request->validated();
        $validated['product_type'] = Course::TYPE_BOOK;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_path'] = $upload->execute($request->file('thumbnail'));
        }

        if ($request->boolean('remove_download_file') && $book->download_file_path) {
            Storage::disk('public')->delete($book->download_file_path);
            $validated['download_file_path'] = null;
        } elseif ($request->hasFile('download_file')) {
            if ($book->download_file_path) {
                Storage::disk('public')->delete($book->download_file_path);
            }
            $validated['download_file_path'] = $request->file('download_file')->store('books', 'public');
        }

        $update->execute($book, $validated);

        return back()->with('status', 'Book updated.');
    }

    public function destroy(Course $book, DeleteCourseAction $delete): RedirectResponse
    {
        abort_unless(($book->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK, 404);
        $this->authorize('delete', $book);

        if ($book->download_file_path) {
            Storage::disk('public')->delete($book->download_file_path);
        }

        $delete->execute($book);

        return redirect()->route('dashboard.books.index')->with('status', 'Book deleted.');
    }

    public function publish(Course $book, PublishCourseAction $publish): RedirectResponse
    {
        abort_unless(($book->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK, 404);
        $this->authorize('publish', $book);
        $publish->execute($book);

        return back()->with('status', 'Book published.');
    }

    public function unpublish(Course $book, UnpublishCourseAction $unpublish): RedirectResponse
    {
        abort_unless(($book->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK, 404);
        $this->authorize('publish', $book);
        $unpublish->execute($book);

        return back()->with('status', 'Book unpublished.');
    }

    private function formOptions(): array
    {
        return [
            'languageOptions' => ReferenceOption::languageOptions(),
            'currencyOptions' => ReferenceOption::currencyOptions(),
        ];
    }
}
