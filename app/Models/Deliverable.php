<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
   protected $fillable = [
       'name',
       'template_days_offset',
   ];

   public function courses()
   {
    return $this->belongsToMany(Course::class, 'course_deliverable')
    ->withPivot('due_date', 'is_done', 'missed_due_date_count', 'date_completed')
    ->using(CourseDeliverable::class);
   }
}
