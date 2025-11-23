<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\User;
use App\Models\Deliverable;
use Carbon\Carbon;

class CourseRepository
{
    public function getAllForAdmin()
    {
        return Course::all()->load(['users:id,first_name,last_name']);
    }

    public function getForUser(User $user)
    {
        return $user->courses()->get();
    }
    
    public function countActiveCourses()
    {
        return Course::where('status', 'active')->count();
    }

    public function create(array $data)
    {
        return Course::create($data);
    }

    public function addObjective(Course $course, array $objectiveData)
    {
        return $course->objectives()->create($objectiveData);
    }

    public function syncUsers(Course $course, array $users)
    {
        $course->users()->detach();
        foreach ($users as $user) {
            $course->users()->attach($user['id'], ['role' => $user['role']]);
        }
        return $course->users;
    }

    public function getById(Course $course, array $relations = [])
    {
        return $course->load($relations);
    }

    public function update(Course $course, array $data)
    {
        return $course->update($data);
    }

    public function deleteObjectives(Course $course)
    {
        return $course->objectives()->delete();
    }

    public function delete(Course $course)
    {
        return $course->delete();
    }

    public function attachAllDeliverables(Course $course)
    {
        $deliverables = Deliverable::all();
        $startDate = Carbon::parse($course->start);
        
        $pivotData = [];
        foreach ($deliverables as $deliverable) {
            $pivotData[$deliverable->id] = [
                'due_date' => $startDate->copy()->addDays($deliverable->template_days_offset),
                'is_done' => false,
                'missed_due_date_count' => 0,
            ];
        }
        
        return $course->deliverables()->attach($pivotData);
    }
}
