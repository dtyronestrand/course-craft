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
        $course = $this->courseRepository->create([
            'prefix' => $data['prefix'],
            'number' => $data['number'],
            'title' => $data['title'],
        ]);

        if (isset($data['objectives'])) {
            foreach ($data['objectives'] as $objective) {
                $this->courseRepository->addObjective($course, $objective);
            }
        }

        if (isset($data['users'])) {
            $this->courseRepository->syncUsers($course, $data['users']);
        }

        return $course;
    }
}
