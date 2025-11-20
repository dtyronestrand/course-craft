<?php

namespace App\Actions\CoursePages;

use App\Models\CoursePage;
use App\Repositories\CoursePageRepository;

class DeleteCoursePageAction
{
    protected $coursePageRepository;

    public function __construct(CoursePageRepository $coursePageRepository)
    {
        $this->coursePageRepository = $coursePageRepository;
    }

    public function execute(CoursePage $coursePage)
    {
        return $this->coursePageRepository->delete($coursePage);
    }
}
