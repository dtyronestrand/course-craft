<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseObjective;

class CourseModuleRepository
{
    public function findCourse(int $courseId): Course
    {
        return Course::findOrFail($courseId);
    }

    public function createModule(Course $course, array $data): CourseModule
    {
        return $course->modules()->create($data);
    }

    public function updateModule(CourseModule $module, array $data): bool
    {
        return $module->update($data);
    }

    public function deleteModule(CourseModule $module): bool
    {
        $courseId = $module->course_id;
        $deletedOrderIndex = $module->order_index;

        if ($module->delete()) {
            CourseModule::where('course_id', $courseId)
                ->where('order_index', '>', $deletedOrderIndex)
                ->decrement('order_index');
            return true;
        }
        return false;
    }

    public function getCourseObjectiveIds(int $courseId, array $numbers): array
    {
        return CourseObjective::whereIn('number', $numbers)
            ->where('course_id', $courseId)
            ->pluck('id')
            ->all();
    }
}
