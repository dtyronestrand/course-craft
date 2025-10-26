<?php

namespace App\Providers;

use App\Models\CourseDiscussion;
use App\Observers\CourseDiscussionObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        //
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        CourseDiscussion::observe(CourseDiscussionObserver::class);
    }
}
