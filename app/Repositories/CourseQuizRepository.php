<?php

namespace App\Repositories;

use App\Models\CourseQuiz;
use App\Models\ModuleItem;

class CourseQuizRepository
{
    public function create(array $data)
    {
        return CourseQuiz::create($data);
    }

    public function createModuleItem(array $data)
    {
        return ModuleItem::create($data);
    }
    public function getMaxOrderIndex(int $moduleId)
    {
        return ModuleItem::where('course_module_id', $moduleId)->max('order_index') ?? -1;
    }

    public function update(CourseQuiz $courseQuiz, array $data)
    {
        return $courseQuiz->update($data);
    }

    public function delete(CourseQuiz $courseQuiz)
    {
        return $courseQuiz->delete();
    }
}
