<?php

namespace App\Services;

use App\Actions\CourseAssignments\CreateCourseAssignmentAction;
use App\Actions\CourseAssignments\UpdateCourseAssignmentAction;
use App\Actions\CourseAssignments\DeleteCourseAssignmentAction;
use App\Models\CourseAssignment;
use App\Repositories\CourseAssignmentRepository;

class CourseAssignmentService
{
    protected $courseAssignmentRepository;

    public function __construct(CourseAssignmentRepository $courseAssignmentRepository)
    {
        $this->courseAssignmentRepository = $courseAssignmentRepository;
    }

    public function createAssignment(array $data)
    {
        return (new CreateCourseAssignmentAction($this->courseAssignmentRepository))->execute($data);
    }

    public function updateAssignment(CourseAssignment $courseAssignment, array $data)
    {
        return (new UpdateCourseAssignmentAction($this->courseAssignmentRepository))->execute($courseAssignment, $data);
    }

    public function deleteAssignment(CourseAssignment $courseAssignment)
    {
        return (new DeleteCourseAssignmentAction($this->courseAssignmentRepository))->execute($courseAssignment);
    }
}
