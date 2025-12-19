<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/instructor', [InstructorController::class, 'show'])->name('instructor.show');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about')->name('pages.about');
Route::get('/terms', [PageController::class, 'show'])->defaults('slug', 'terms')->name('pages.terms');
Route::get('/privacy', [PageController::class, 'show'])->defaults('slug', 'privacy')->name('pages.privacy');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{course:slug}/enroll', [CourseController::class, 'enroll'])
    ->middleware('auth')
    ->name('courses.enroll');

Route::get('/courses/{course:slug}/lessons/{lesson:slug}', [LessonController::class, 'show'])
    ->middleware(['auth', \App\Http\Middleware\EnsureUserIsEnrolled::class])
    ->scopeBindings()
    ->name('lessons.show');
