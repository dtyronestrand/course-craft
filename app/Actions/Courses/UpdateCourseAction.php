<?php

namespace App\Actions\Courses;

use App\Models\Course;
use App\Repositories\CourseRepository;

class UpdateCourseAction
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function execute(Course $course, array $data)
    {
        $this->courseRepository->update($course, [
            'prefix' => $data['prefix'],
            'number' => $data['number'],
            'title' => $data['title'],
        ]);

        if (isset($data['objectives'])) {
            $this->courseRepository->deleteObjectives($course);
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
