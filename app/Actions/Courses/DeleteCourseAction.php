<?php

namespace App\Actions\Courses;

use App\Models\Course;
use App\Repositories\CourseRepository;

class DeleteCourseAction
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(Course $course)
    {
        return $this->courseRepository->delete($course);
    }
}
