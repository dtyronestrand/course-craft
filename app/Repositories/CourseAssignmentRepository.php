<?php

namespace App\Repositories;

use App\Models\CourseAssignment;
use App\Models\ModuleItem;

class CourseAssignmentRepository
{
    public function create(array $data)
    {
        return CourseAssignment::create($data);
    }

    public function createModuleItem(array $data)
    {
        return ModuleItem::create($data);
    }
    public function getMaxOrderIndex(int $moduleId)
    {
        return ModuleItem::where('course_module_id', $moduleId)->max('order_index') ?? -1;
    }

    public function update(CourseAssignment $courseAssignment, array $data)
    {
        return $courseAssignment->update($data);
    }

    public function delete(CourseAssignment $courseAssignment)
    {
        return $courseAssignment->delete();
    }
}
