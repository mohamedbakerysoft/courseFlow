<?php

namespace App\Http\Controllers;

use App\Actions\Instructor\ShowInstructorProfileAction;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function show(ShowInstructorProfileAction $action): View
    {
        [$instructor, $courses, $links] = $action->execute();

        return view('instructor.show', compact('instructor', 'courses', 'links'));
    }
}
