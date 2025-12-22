<?php

namespace App\Http\Middleware;

use App\Actions\Courses\CheckUserEnrollmentAction;
use App\Models\Course;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsEnrolled
{
    public function __construct(private CheckUserEnrollmentAction $checker) {}

    public function handle(Request $request, Closure $next): Response
    {
        /** @var Course $course */
        $course = $request->route('course');
        if (! $this->checker->execute($request->user(), $course)) {
            abort(403);
        }

        return $next($request);
    }
}
