<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class CourseAssessment extends Model
{
 protected $fillable = [
    'title',
    'type',
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

 public function objectives(): MorphToMany
 {
    return $this->morphToMany(ModuleObjective::class, 'alignable', 'module_objective_alignments');
 }
}
