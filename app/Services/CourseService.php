<?php

namespace App\Services;

use App\Actions\Courses\CreateCourseAction;
use App\Actions\Courses\UpdateCourseAction;
use App\Actions\Courses\DeleteCourseAction;
use App\Models\Course;
use App\Models\User;
use App\Repositories\CourseRepository;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getCoursesForUser(User $user)
    {
        if ($user->is_admin) {
            return $this->courseRepository->getAllForAdmin();
        }

        return $this->courseRepository->getForUser($user);
    }

    public function createCourse(array $data)
    {
        return (new CreateCourseAction($this->courseRepository))->execute($data);
    }

    public function updateCourse(Course $course, array $data)
    {
        return (new UpdateCourseAction($this->courseRepository))->execute($course, $data);
    }

    public function getCourseWithDetails(Course $course)
    {
        return $this->courseRepository->getById($course, ['modules.courseObjectives', 'modules.assessments.objectives', 'modules.module_objectives', 'modules.instructions', 'modules.materials', 'modules.needs', 'users', 'modules.items.itemable', 'objectives']);
    }
    public function getCourseForMap(Course $course)
    {
        return $this->courseRepository->getById($course, ['modules.courseObjectives', 'modules.assessments.objectives', 'modules.module_objectives', 'modules.instructions', 'modules.materials', 'modules.needs', 'users', 'objectives']);
    }
    public function getCourseForStoryboard(Course $course)
    {
        return $this->courseRepository->getById($course, ['modules.courseObjectives', 'modules.module_objectives', 'users', 'modules.items.itemable', 'objectives']);
    }

    public function deleteCourse(Course $course)
    {
        return (new DeleteCourseAction($this->courseRepository))->execute($course);
    }
}
