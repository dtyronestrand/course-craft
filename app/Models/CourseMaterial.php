<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
class CourseMaterial extends Model
{
    protected $fillable = [
        'title',
        'type',
        'url',
        'content',
        'name',
        'course_id',
    ];

    public function objectives() : MorphToMany
    {
        return $this->morphToMany(ModuleObjective::class, 'alignable', 'module_objective_alignments');
    }

    public function module() : BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }
}
