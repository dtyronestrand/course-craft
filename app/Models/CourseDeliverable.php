<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseDeliverable extends Pivot
{
 public $incrementing = true;   
 protected $table = 'course_deliverable';

 protected $dates = [
   'default_due_date',
   'override_due_date',
 ];

 public function course()
 {
    return $this->belongsTo(Course::class);
 }

   public function deliverable()
   {
      return $this->belongsTo(Deliverable::class);
   }
public function getActualDueDateAttribute()
 {
    return $this->override_due_date ?? $this->default_due_date;
 }

 public function scopeNeedsAttention($query)
 {
    return $query->where('is_done', false)
                 ->where('missed_due_date_count', '>=', 2);
 }
}
