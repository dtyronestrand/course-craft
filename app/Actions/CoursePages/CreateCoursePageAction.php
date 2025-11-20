<?php

namespace App\Actions\CoursePages;

use App\Repositories\CoursePageRepository;

class CreateCoursePageAction
{
    protected $coursePageRepository;

    public function __construct(CoursePageRepository $coursePageRepository)
    {
        $this->coursePageRepository = $coursePageRepository;
    }

    public function execute(array $data)
    {
        $coursePage = $this->coursePageRepository->create([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        $maxOrderIndex = $this->coursePageRepository->getMaxOrderIndex($data['module']);

        $this->coursePageRepository->createModuleItem([
            'course_module_id' => $data['module'],
            'itemable_id' => $coursePage->id,
            'itemable_type' => 'page',
            'order_index' => $maxOrderIndex + 1,
        ]);

        return $coursePage;
    }
}
