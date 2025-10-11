<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseAssessment extends Model
{
 protected $fillable = [
    'title',
    'type',
    'aligned_module_objectives',
    'purpose',
    'criteria',
    'point_value',
    'time_limit',
 ];

 protected $casts = [
    'aligned_module_objectives' => 'array',
 ];

 public function module()
 {
    return $this->belongsTo(CourseModule::class, 'module_id');
 }
}
