<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\Users\GrantCourseAccessAction;
use App\Actions\Dashboard\Users\ListUsersAction;
use App\Actions\Dashboard\Users\UpdateUserStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\GrantCourseAccessRequest;
use App\Http\Requests\Dashboard\Users\UpdateUserStatusRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(ListUsersAction $list): View
    {
        $users = $list->execute(20);

        return view('dashboard.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $enrolledCount = $user->courses()->count();
        $enrolledCourses = $user->courses()->select(['courses.id', 'courses.slug', 'courses.title'])->get();
        $courses = Course::published()->select(['id', 'slug', 'title'])->orderBy('title')->get();

        return view('dashboard.users.show', compact('user', 'enrolledCount', 'enrolledCourses', 'courses'));
    }

    public function updateStatus(User $user, UpdateUserStatusRequest $request, UpdateUserStatusAction $action): RedirectResponse
    {
        $disabled = $request->boolean('is_disabled');
        $action->execute($user, $disabled);

        return redirect()->route('dashboard.users.show', $user)->with('status', 'updated');
    }

    public function grantAccess(User $user, GrantCourseAccessRequest $request, GrantCourseAccessAction $grant): RedirectResponse
    {
        $courseId = (int) ($request->validated()['course_id'] ?? $request->input('course_id'));
        $course = Course::published()->where('id', $courseId)->firstOrFail();
        $grant->execute($user, $course);

        return redirect()->route('dashboard.users.show', $user)->with('status', 'granted');
    }
}
