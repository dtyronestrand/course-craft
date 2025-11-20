<?php

namespace App\Actions\CourseAssignments;

use App\Models\CourseAssignment;
use App\Repositories\CourseAssignmentRepository;

class UpdateCourseAssignmentAction
{
    protected $courseAssignmentRepository;

    public function __construct(CourseAssignmentRepository $courseAssignmentRepository)
    {
        $this->courseAssignmentRepository = $courseAssignmentRepository;
    }

    public function execute(CourseAssignment $courseAssignment, array $data)
    {
        return $this->courseAssignmentRepository->update($courseAssignment, [
            'title' => $data['title'],
            'purpose' => $data['purpose'],
            'criteria' => $data['criteria'],
            'settings' => $data['settings'],
        ]);
    }
}
