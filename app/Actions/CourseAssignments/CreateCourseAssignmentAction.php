<?php

namespace App\Actions\CourseAssignments;

use App\Repositories\CourseAssignmentRepository;

class CreateCourseAssignmentAction
{
    protected $courseAssignmentRepository;

    public function __construct(CourseAssignmentRepository $courseAssignmentRepository)
    {
        $this->courseAssignmentRepository = $courseAssignmentRepository;
    }

    public function execute(array $data)
    {
        $courseAssignment = $this->courseAssignmentRepository->create([
            'title' => $data['title'],
            'purpose' => $data['purpose'],
            'criteria' => $data['criteria'],
            'settings' => $data['settings'],
        ]);

        $maxOrderIndex = $this->courseAssignmentRepository->getMaxOrderIndex($data['module']);

        $this->courseAssignmentRepository->createModuleItem([
            'course_module_id' => $data['module'],
            'itemable_id' => $courseAssignment->id,
            'itemable_type' => 'assignment',
            'order_index' => $maxOrderIndex + 1,
        ]);

        return $courseAssignment;
    }
}
