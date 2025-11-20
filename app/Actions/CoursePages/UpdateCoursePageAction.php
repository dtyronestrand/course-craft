<?php

namespace App\Actions\CoursePages;

use App\Models\CoursePage;
use App\Repositories\CoursePageRepository;

class UpdateCoursePageAction
{
    protected $coursePageRepository;

    public function __construct(CoursePageRepository $coursePageRepository)
    {
        $this->coursePageRepository = $coursePageRepository;
    }

    public function execute(CoursePage $coursePage, array $data)
    {
        return $this->coursePageRepository->update($coursePage, [
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }
}
