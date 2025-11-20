<?php

namespace App\Actions\CourseDiscussions;

use App\Repositories\CourseDiscussionRepository;

class CreateCourseDiscussionAction
{
    protected $courseDiscussionRepository;

    public function __construct(CourseDiscussionRepository $courseDiscussionRepository)
    {
        $this->courseDiscussionRepository = $courseDiscussionRepository;
    }

    public function execute(array $data)
    {
        $courseDiscussion = $this->courseDiscussionRepository->create([
            'title' => $data['title'],
            'prompt' => $data['prompt'],
            'settings' => $data['settings'],
        ]);

        $maxOrderIndex = $this->courseDiscussionRepository->getMaxOrderIndex($data['module']);

        $this->courseDiscussionRepository->createModuleItem([
            'course_module_id' => $data['module'],
            'itemable_id' => $courseDiscussion->id,
            'itemable_type' => 'discussion',
            'order_index' => $maxOrderIndex + 1,
        ]);

        return $courseDiscussion;
    }
}
