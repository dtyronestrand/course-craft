<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course;
use App\Models\CourseAssessment;
use App\Models\CourseInstruction;
use App\Models\CourseMaterial;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseModule extends Model
{
protected $fillable =[
    'title',
    'learning_objectives',
    'order_index',
];

protected $casts = [
    'learning_objectives' => 'array',
];

public function course() : BelongsTo
{
    return $this->belongsTo(Course::class);
}

public function assessments() : HasMany
{
    return $this->hasMany(CourseAssessment::class, 'module_id');
}

public function instructions() : HasMany
{
    return $this->hasMany(CourseInstruction::class, 'module_id');
}
public function materials() : HasMany
{
    return $this->hasMany(CourseMaterial::class, 'module_id');
}
}


