<?php

namespace App\Repositories;

use App\Models\CourseDiscussion;
use App\Models\ModuleItem;

class CourseDiscussionRepository
{
    public function create(array $data)
    {
        return CourseDiscussion::create($data);
    }

    public function createModuleItem(array $data)
    {
        return ModuleItem::create($data);
    }
    public function getMaxOrderIndex(int $moduleId)
    {
        return ModuleItem::where('course_module_id', $moduleId)->max('order_index') ?? -1;
    }

    public function update(CourseDiscussion $courseDiscussion, array $data)
    {
        return $courseDiscussion->update($data);
    }

    public function delete(CourseDiscussion $courseDiscussion)
    {
        return $courseDiscussion->delete();
    }
}
