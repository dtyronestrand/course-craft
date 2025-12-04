<?php

namespace App\Services;

use App\Actions\Courses\CreateCourseAction;
use App\Actions\Courses\UpdateCourseAction;
use App\Actions\Courses\DeleteCourseAction;
use App\Models\Course;
use App\Models\User;
use App\Repositories\CourseRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function createCourse(array $data)
    {
        return (new CreateCourseAction($this->courseRepository))->execute($data);
    }

     public function countActiveCourses()
    {
        return $this->courseRepository->countActiveCourses();
    }
       public function countPendingCourses()
    {
        return $this->courseRepository->countPendingCourses();
    }
      public function coursesNeedAttention()
    {
        return $this->courseRepository->coursesNeedAttention();
    }

    public function courseStatusCounts()
    {
        return $this->courseRepository->courseStatusCounts();
    }
  
    public function coursesByPrefix()
    {
        return $this->courseRepository->coursesByPrefix();
    }

            public function deleteCourse(Course $course)
    {
        return (new DeleteCourseAction($this->courseRepository))->execute($course);
    }
    

    public function getAllCourses()
    {
        return $this->courseRepository->getAllForAdmin(['users:id,first_name,last_name','delivrables']);
    }
        public function getCourseForMap(Course $course)
    {
        return $this->courseRepository->getById($course, ['modules.courseObjectives', 'modules.assessments.objectives', 'modules.module_objectives', 'modules.instructions.objectives', 'modules.materials.objectives', 'modules.needs.objectives', 'users', 'objectives']);
    }

        public function getCourseForStoryboard(Course $course)
    {
        return $this->courseRepository->getById($course, ['modules.courseObjectives', 'modules.module_objectives', 'users', 'modules.items.itemable', 'objectives']);
    }

    public function getCoursesForUser(User $user)
    {
        if ($user->is_admin) {
            return $this->courseRepository->getAllForAdmin();
        }

        return $this->courseRepository->getForUser($user);
    }

    public function getCourseWithDetails(Course $course)
    {
        return $this->courseRepository->getById($course, ['modules.courseObjectives', 'modules.assessments.objectives', 'modules.module_objectives', 'modules.instructions.objectives', 'modules.materials.objectives', 'modules.needs.objectives', 'users', 'modules.items.itemable', 'objectives', 'deliverables']);
    }
    
    public function getCourseForAdmin(Course $course) {
        return $this->courseRepository->getById($course, ['deliverables', 'users']);
    }
    public function syncUsers(Course $course, array $users)
    {
        return $this->courseRepository->syncUsers($course, $users);
    }
    public function updateCourse(Course $course, array $data)
    {
        return (new UpdateCourseAction($this->courseRepository))->execute($course, $data);
    }
   
    public function updateCourseDeliverable(Course $course, $deliverable, array $pivotData)
    {
        return $this->courseRepository->updatePivot($course, $deliverable, $pivotData);
    }
    public function averageCompletionRate()
    {
        $courses = $this->courseRepository->getCurrentCycleCompletedCourses();
        if ($courses->isEmpty()) {
            return 'No data';
        } else {
            $daysToComplete = [];
            foreach($courses as $course) {
               $start = \Carbon\Carbon::parse($course->start);
               $completion = \Carbon\Carbon::parse($course->completion_date);
               $daysTaken = $start->diffInDays($completion);
              return $daysToComplete[] = $daysTaken;
            }
            $averageDays = array_sum($daysToComplete) / count($daysToComplete);
            return round($averageDays, 2) . ' days';
        }
    }
}
