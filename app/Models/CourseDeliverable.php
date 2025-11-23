<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseDeliverable extends Pivot
{
 public $incrementing = true;   
 protected $table = 'course_deliverable';

 public function scopeNeedsAttention($query)
 {
    return $query->where('is_done', false)
                 ->where('missed_due_date_count', '>=', 2);
 }
}
