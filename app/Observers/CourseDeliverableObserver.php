<?php

namespace App\Observers;

use App\Models\CourseDeliverable;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;

class CourseDeliverableObserver
{
    public function __construct(private ActivityService $activityService){}

    public function updated(CourseDeliverable $courseDeliverable): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        $courseDeliverable->load(['deliverable', 'course']);
        if ($user) {
            $this->activityService->log(
                $user->id,
                $courseDeliverable,
                'deliverable_updated',
                $user->first_name . ' marked deliverable ' . $courseDeliverable->deliverable->name . ' completed for course ' . $courseDeliverable->course->title
            );
        }
    }
}