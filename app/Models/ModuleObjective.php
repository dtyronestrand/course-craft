<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ModuleObjective extends Model
{
    protected $fillable = [
        'module_id',
        'number',
        'objective',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

        public function assessments(): MorphToMany
    {
        return $this->morphedByMany(CourseAssessment::class, 'alignable', 'objective_alignments');
    }

    public function materials(): MorphToMany
    {
        return $this->morphedByMany(CourseMaterial::class, 'alignable', 'objective_alignments');
    }

    public function instructions(): MorphToMany
    {
        return $this->morphedByMany(CourseInstruction::class, 'alignable', 'objective_alignments');
    }

}