<?php

namespace App\Actions\Courses;

use App\Repositories\CourseRepository;

class CreateCourseAction
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(array $data)
    {
        $course = $this->courseRepository->create($data);

        if (isset($data['objectives'])) {
            foreach ($data['objectives'] as $objective) {
                $this->courseRepository->addObjective($course, $objective);
            }
        }

        if (isset($data['users'])) {
            $this->courseRepository->syncUsers($course, $data['users']);
        }
        $this->courseRepository->attachAllDeliverables($course);

        return $course;
    }
}
