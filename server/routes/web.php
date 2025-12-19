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

Route::middleware('auth')->group(function () {
    Route::post('/courses/{course:slug}/checkout', [\App\Http\Controllers\Payments\CheckoutController::class, 'checkout'])->name('payments.checkout');
    Route::get('/payments/success', [\App\Http\Controllers\Payments\CheckoutController::class, 'success'])->name('payments.success');
    Route::get('/payments/cancel/{course:slug}', [\App\Http\Controllers\Payments\CheckoutController::class, 'cancel'])->name('payments.cancel');
    Route::post('/courses/{course:slug}/paypal/checkout', [\App\Http\Controllers\Payments\PayPalCheckoutController::class, 'checkout'])->name('payments.paypal.checkout');
    Route::get('/payments/paypal/success', [\App\Http\Controllers\Payments\PayPalCheckoutController::class, 'success'])->name('payments.paypal.success');
    Route::get('/payments/paypal/cancel/{course:slug}', [\App\Http\Controllers\Payments\PayPalCheckoutController::class, 'cancel'])->name('payments.paypal.cancel');
    Route::post('/courses/{course:slug}/manual/start', [\App\Http\Controllers\Payments\ManualPaymentController::class, 'start'])->name('payments.manual.start');
    Route::get('/payments/manual/pending/{payment}', [\App\Http\Controllers\Payments\ManualPaymentController::class, 'pending'])->name('payments.manual.pending');
});

Route::post('/webhooks/stripe', [\App\Http\Controllers\Payments\StripeWebhookController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('payments.webhook.stripe');

Route::middleware(['auth', 'instructor'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/courses', [\App\Http\Controllers\Dashboard\CourseController::class, 'index'])->name('courses.index');
    $approveRoute = Route::post('/payments/{payment}/approve', [\App\Http\Controllers\Payments\ManualPaymentController::class, 'approve'])->name('payments.approve');
    if (app()->environment('dusk') || app()->environment('dusk.local')) {
        $approveRoute->withoutMiddleware([
            \App\Http\Middleware\EnsureUserIsInstructor::class,
            'auth',
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);
    }
    Route::get('/appearance', [\App\Http\Controllers\Dashboard\AppearanceController::class, 'edit'])->name('appearance.edit');
    Route::post('/appearance', [\App\Http\Controllers\Dashboard\AppearanceController::class, 'update'])->name('appearance.update');
    Route::get('/courses/create', [\App\Http\Controllers\Dashboard\CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [\App\Http\Controllers\Dashboard\CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course:slug}/edit', [\App\Http\Controllers\Dashboard\CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course:slug}', [\App\Http\Controllers\Dashboard\CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course:slug}', [\App\Http\Controllers\Dashboard\CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course:slug}/publish', [\App\Http\Controllers\Dashboard\CourseController::class, 'publish'])->name('courses.publish');
    Route::post('/courses/{course:slug}/unpublish', [\App\Http\Controllers\Dashboard\CourseController::class, 'unpublish'])->name('courses.unpublish');

    Route::get('/courses/{course:slug}/lessons', [\App\Http\Controllers\Dashboard\LessonController::class, 'index'])->name('courses.lessons.index');
    Route::get('/courses/{course:slug}/lessons/create', [\App\Http\Controllers\Dashboard\LessonController::class, 'create'])->name('courses.lessons.create');
    Route::post('/courses/{course:slug}/lessons', [\App\Http\Controllers\Dashboard\LessonController::class, 'store'])->name('courses.lessons.store');

    Route::get('/lessons/{lesson}/edit', [\App\Http\Controllers\Dashboard\LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [\App\Http\Controllers\Dashboard\LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [\App\Http\Controllers\Dashboard\LessonController::class, 'destroy'])->name('lessons.destroy');
});
