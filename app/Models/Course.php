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

    ] ;



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

    public function deliverables()
    {
      return $this->belongsToMany(Deliverable::class, 'course_deliverable')
      ->withPivot('due_date', 'is_done', 'missed_due_date_count', 'date_completed')
      ->using(CourseDeliverable::class);
    }

      protected static function boot()
  {
    parent::boot();
    
    static::deleting(function ($course) {
      $course->course_user()->delete();
    });
  }
}
