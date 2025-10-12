<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class CourseObjective extends Model
{
    protected $fillable=[
        'number',
        'objective',
    ];

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function modules() : BelongsToMany
    {
        return $this->belongsToMany(CourseModule::class, 'course_objective_module');
    }
}
