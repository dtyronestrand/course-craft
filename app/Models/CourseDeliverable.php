<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseDeliverable extends Pivot
{
 public $incrementing = true;   
 protected $table = 'course_deliverable';

 protected $casts = [
    'is_done' => 'boolean',
    'due_date' => 'date',
    'date_completed' => 'date',
 ];

 public function deliverable(): BelongsTo
 {
    return $this->belongsTo(Deliverable::class);
 }

 public function course(): BelongsTo
 {
    return $this->belongsTo(Course::class);
 }

 public function scopeNeedsAttention($query)
 {
    return $query->where('is_done', false)
                 ->where('missed_due_date_count', '>=', 2);
 }
}
