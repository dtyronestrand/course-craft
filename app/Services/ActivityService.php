<?php

namespace App\Services;

use App\Events\ActivityCreated;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;

class ActivityService
{
    public function log(int $userId, Model $subject, string $action, ?string $description = null, ?array $metadata = null): Activity
    {
        $activity = Activity::create([
            'user_id' => $userId,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
        ]);
        $activity->load('user');
        broadcast(new ActivityCreated($activity));
      

        return $activity;
    }

    public function getRecentActivities(int $limit = 10): array
    {
        return Activity::with('user')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn($activity) => [
                'id' => $activity->id,
                'user' => [
                    'name' => $activity->user->first_name . ' ' . $activity->user->last_name,
                    'initials' => strtoupper(substr($activity->user->first_name, 0, 1) . substr($activity->user->last_name, 0, 1)),
                ],
                'action' => $activity->action,
                'description' => $activity->description,
                'timestamp' => $activity->created_at->diffForHumans(),
                'created-at' => $activity->created_at->toISOString(),
            ])
            ->toArray();
    }
}