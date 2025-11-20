<?php

namespace App\Services;

use App\Actions\CourseDiscussions\CreateCourseDiscussionAction;
use App\Actions\CourseDiscussions\UpdateCourseDiscussionAction;
use App\Actions\CourseDiscussions\DeleteCourseDiscussionAction;
use App\Models\CourseDiscussion;
use App\Repositories\CourseDiscussionRepository;

class CourseDiscussionService
{
    protected $courseDiscussionRepository;

    public function __construct(CourseDiscussionRepository $courseDiscussionRepository)
    {
        $this->courseDiscussionRepository = $courseDiscussionRepository;
    }

    public function createDiscussion(array $data)
    {
        return (new CreateCourseDiscussionAction($this->courseDiscussionRepository))->execute($data);
    }

    public function updateDiscussion(CourseDiscussion $courseDiscussion, array $data)
    {
        return (new UpdateCourseDiscussionAction($this->courseDiscussionRepository))->execute($courseDiscussion, $data);
    }

    public function deleteDiscussion(CourseDiscussion $courseDiscussion)
    {
        return (new DeleteCourseDiscussionAction($this->courseDiscussionRepository))->execute($courseDiscussion);
    }
}
