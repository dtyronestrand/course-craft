<?php

namespace App\Services;

use App\Actions\CoursePages\CreateCoursePageAction;
use App\Actions\CoursePages\UpdateCoursePageAction;
use App\Actions\CoursePages\DeleteCoursePageAction;
use App\Models\CoursePage;
use App\Repositories\CoursePageRepository;

class CoursePageService
{
    protected $coursePageRepository;

    public function __construct(CoursePageRepository $coursePageRepository)
    {
        $this->coursePageRepository = $coursePageRepository;
    }

    public function createPage(array $data)
    {
        return (new CreateCoursePageAction($this->coursePageRepository))->execute($data);
    }

    public function updatePage(CoursePage $coursePage, array $data)
    {
        return (new UpdateCoursePageAction($this->coursePageRepository))->execute($coursePage, $data);
    }

    public function deletePage(CoursePage $coursePage)
    {
        return (new DeleteCoursePageAction($this->coursePageRepository))->execute($coursePage);
    }
}
