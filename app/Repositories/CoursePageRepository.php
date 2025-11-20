<?php

namespace App\Repositories;

use App\Models\CoursePage;
use App\Models\ModuleItem;

class CoursePageRepository
{
    public function create(array $data)
    {
        return CoursePage::create($data);
    }

    public function createModuleItem(array $data)
    {
        return ModuleItem::create($data);
    }
    public function getMaxOrderIndex(int $moduleId)
    {
        return ModuleItem::where('course_module_id', $moduleId)->max('order_index') ?? -1;
    }

    public function update(CoursePage $coursePage, array $data)
    {
        return $coursePage->update($data);
    }

    public function delete(CoursePage $coursePage)
    {
        return $coursePage->delete();
    }
}
