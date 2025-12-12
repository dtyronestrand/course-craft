<?php

namespace App\Repositories;

use App\Models\Deliverable;
use App\Models\Course;

class DeliverableRepository
{
    public function create(array $data)
    {
        return Deliverable::create($data);
    }
    public function getAll()
    {
        return Deliverable::orderBy('template_days_offset')->get();
    }
    
    public function update(Deliverable $deliverable, array $data)
    {
        return $deliverable->update($data);
    }

    public function delete(Deliverable $deliverable)
    {
        return $deliverable->delete();
    }

    public function attachToCourse(Course $course, Deliverable $deliverable, array $pivotData = [])
    {
        return $course->deliverables()->attach($deliverable->id, $pivotData);
    }

    public function detachFromCourse(Course $course, Deliverable $deliverable)
    {
        return $course->deliverables()->detach($deliverable->id);
    }

    public function updatePivot(Course $course, Deliverable $deliverable, array $pivotData)
    {
        return $course->deliverables()->updateExistingPivot($deliverable->id, $pivotData);
    }

    public function syncCourseDeliverables(Course $course, array $deliverables)
    {
        return $course->deliverables()->sync($deliverables);
    }
}