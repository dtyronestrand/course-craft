<?php

namespace App\Actions\CourseDiscussions;

use App\Models\CourseDiscussion;
use App\Repositories\CourseDiscussionRepository;

class DeleteCourseDiscussionAction
{
    protected $courseDiscussionRepository;

    public function __construct(CourseDiscussionRepository $courseDiscussionRepository)
    {
        $this->courseDiscussionRepository = $courseDiscussionRepository;
    }

    public function execute(CourseDiscussion $courseDiscussion)
    {
        return $this->courseDiscussionRepository->delete($courseDiscussion);
    }
}
