<?php

namespace App\Actions\Dashboard\Users;

use App\Actions\Courses\EnrollUserInCourseAction;
use App\Models\Course;
use App\Models\User;

class GrantCourseAccessAction
{
    protected EnrollUserInCourseAction $enroll;

    public function __construct(EnrollUserInCourseAction $enroll)
    {
        $this->enroll = $enroll;
    }

    public function execute(User $user, Course $course): void
    {
        $this->enroll->execute($user, $course);
    }
}
