<?php

namespace App\Actions\Dashboard\Users;

use App\Actions\Courses\RevokeUserFromCourseAction;
use App\Models\Course;
use App\Models\User;

class RevokeCourseAccessAction
{
    protected RevokeUserFromCourseAction $revoke;

    public function __construct(RevokeUserFromCourseAction $revoke)
    {
        $this->revoke = $revoke;
    }

    public function execute(User $user, Course $course): void
    {
        $this->revoke->execute($user, $course);
    }
}
