<?php

namespace App\Observers;
use App\Models\CourseDiscussion;
use Illuminate\Support\Facades\Log;
class CourseDiscussionObserver
{
 public function deleting(CourseDiscussion $discussion)
 {
   $discussion->moduleItem?->delete();
 }
}

