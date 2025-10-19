<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ModuleItem extends Model
{
   public function module(): BelongsTo
   {
       return $this->belongsTo(CourseModule::class, 'course_module_id');
   }

   public function assignments(): MorphToMany
    {
         return $this->morphedByMany(CourseAssignment::class, 'itemable', 'module_items');
    }

    public function pages(): MorphToMany
    {
         return $this->morphedByMany(CoursePage::class, 'itemable', 'module_items');
    }

    public function discussion(): MorphToMany
    {
         return $this->morphedByMany(CourseDiscussion::class, 'itemable', 'module_items');
    }

    public function quizzes(): MorphToMany
    {
         return $this->morphedByMany(CourseQuiz::class, 'itemable', 'module_items');
    }
    
}
