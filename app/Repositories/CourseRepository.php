<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\User;
use App\Models\Deliverable;
use App\Models\DevelopmentCycle;
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
        return Course::whereNotIn('status', ['pending', 'completed'])->count();
    }
    public function courseStatusCounts()
    {
        return Course::selectRaw('status, COUNT(*) as count')
            ->whereHas('developmentCycle', function ($query) {
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            })
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
    public function coursesByPrefix()
    {
        return Course::selectRaw('prefix, COUNT(*) as count')
            ->groupBy('prefix')
            ->pluck('count', 'prefix')
            ->toArray();
    }

    public function courseNeedsAttention
    public function countPendingCourses()
    {
        return Course::where('status', 'pending')->count();
    }

    public function create(array $data)
    {
        return Course::create([
            'prefix' => $data['prefix'],
            'number' => $data['number'],
            'title' => $data['title'],
       
            'development_cycle_id' => $data['development_cycle'] ?? null,
        ]);
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
        $course->load('developmentCycle');
        
        $pivotData = [];
        foreach ($deliverables as $deliverable) {
            $pivotData[$deliverable->id] = [
                'due_date' => $course->developmentCycle && $course->developmentCycle->start_date
                    ? Carbon::parse($course->developmentCycle->start_date)->addDays($deliverable->template_days_offset)
                    : null,
                'is_done' => false,
                'missed_due_date_count' => 0,
            ];
        }
        
        $course->deliverables()->sync($pivotData);
    }

    public function updatePivot(Course $course, $deliverable, array $pivotData)
    {
        return $course->deliverables()->updateExistingPivot($deliverable->id, $pivotData);
    }
}
