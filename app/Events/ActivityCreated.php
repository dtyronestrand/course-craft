<?php 

namespace App\Events;

use App\Models\Activity;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Activity $activity)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('activities');
    }

    public function broadcastsWith(): array
    {
        return [
            'id' => $this->activity->id,
           'user' =>[
            'name' => $this->activity->user->first_name . ' ' . $this->activity->user->last_name,
            'initials' => strtoupper(substr($this->activity->user->first_name,0,1) . substr($this->activity->user->last_name,0,1)),
           ], 
              'action' => $this->activity->action,
                'description' => $this->activity->description,
                'timestamp' => $this->activity->created_at->diffForHumans(),
                'created-at' => $this->activity->created_at->toISOString(),
        ];
    }
}