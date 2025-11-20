<?php

namespace App\Actions\CourseDiscussions;

use App\Models\CourseDiscussion;
use App\Repositories\CourseDiscussionRepository;

class UpdateCourseDiscussionAction
{
    protected $courseDiscussionRepository;

    public function __construct(CourseDiscussionRepository $courseDiscussionRepository)
    {
        $this->courseDiscussionRepository = $courseDiscussionRepository;
    }

    public function execute(CourseDiscussion $courseDiscussion, array $data)
    {
        return $this->courseDiscussionRepository->update($courseDiscussion, [
            'title' => $data['title'],
            'prompt' => $data['prompt'],
            'settings' => $data['settings'],
        ]);
    }
}
