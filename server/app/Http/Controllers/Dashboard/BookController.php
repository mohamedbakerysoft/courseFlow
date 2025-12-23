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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request, ListInstructorCoursesAction $list): View
    {
        $books = $list->executeByType($request->user(), Course::TYPE_BOOK, 20);

        return view('dashboard.books.index', compact('books'));
    }

    public function create(): View
    {
        return view('dashboard.books.create');
    }

    public function store(Request $request, CreateCourseAction $create, UploadCourseThumbnailAction $upload): RedirectResponse
    {
        $this->authorize('create', Course::class);

        $validated = $this->validatePayload($request);
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

        return view('dashboard.books.edit', compact('book'));
    }

    public function update(Request $request, Course $book, UpdateCourseAction $update, UploadCourseThumbnailAction $upload): RedirectResponse
    {
        abort_unless(($book->product_type ?? Course::TYPE_COURSE) === Course::TYPE_BOOK, 404);
        $this->authorize('update', $book);

        $validated = $this->validatePayload($request, $book);
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

    private function validatePayload(Request $request, ?Course $book = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('courses', 'slug')->ignore($book?->id)],
            'description' => ['nullable', 'string'],
            'thumbnail_path' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'download_file' => ['nullable', 'file', 'mimes:pdf,epub,zip,doc,docx', 'max:10240'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
            'is_free' => ['nullable', 'boolean'],
            'language' => ['nullable', 'string', 'max:12'],
            'remove_download_file' => ['nullable', 'boolean'],
        ]);
    }
}
