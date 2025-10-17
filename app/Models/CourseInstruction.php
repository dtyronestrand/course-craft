<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CourseInstruction extends Model
{
    protected $table = 'course_instruction';
    
    protected $fillable = [
        'title',
        'type',
        'content',
        'name',
    ];

    public function objectives(): MorphToMany
    {
        return $this->morphToMany(ModuleObjective::class, 'alignable', 'module_objective_alignments');
    }

    public function module() : BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }
}
