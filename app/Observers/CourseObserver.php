<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;

class CourseObserver
{
    public function __construct(private ActivityService $activityService)
    {
    }
    public function created(Course $course)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user) {
            $this->activityService->log(
                $user->id,
                $course,
                'created',
                $user->first_name . ' created course ' . $course->title
            );
        }
    }
    public function updated(Course $course)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user) {
            $this->activityService->log(
                $user->id,
                $course,
                'updated',
                $user->first_name . ' updated course ' . $course->title
            );
        }
    }
}