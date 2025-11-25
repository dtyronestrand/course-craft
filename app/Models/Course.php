<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Course extends Model
{
    protected $fillable = [
        'prefix',
        'number',
        'title',
        'start',
        'end',
        'status',
        'development_cycle_id',
    ];



    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function modules() : HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function objectives() : HasMany
    {
        return $this->hasMany(CourseObjective::class);
    }

    public function developmentCycle()
    {
        return $this->belongsTo(DevelopmentCycle::class);
    }

    public function deliverables()
    {
        return $this->belongsToMany(Deliverable::class, 'course_deliverable')
            ->using(CourseDeliverable::class)
            ->withPivot(['id', 'default_due_date', 'override_due_date', 'is_done', 'missed_due_date_count'])
            ->withTimestamps();
    }

    public function courseDeliverables()
    {
        return $this->hasMany(CourseDeliverable::class);
    }

      protected static function boot()
  {
    parent::boot();
    
    static::deleting(function ($course) {
      $course->course_user()->delete();
    });
  }
}
