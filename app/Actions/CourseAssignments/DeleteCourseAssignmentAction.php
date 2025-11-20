<?php

namespace App\Actions\CourseAssignments;

use App\Models\CourseAssignment;
use App\Repositories\CourseAssignmentRepository;

class DeleteCourseAssignmentAction
{
    protected $courseAssignmentRepository;

    public function __construct(CourseAssignmentRepository $courseAssignmentRepository)
    {
        $this->courseAssignmentRepository = $courseAssignmentRepository;
    }

    public function execute(CourseAssignment $courseAssignment)
    {
        return $this->courseAssignmentRepository->delete($courseAssignment);
    }
}
