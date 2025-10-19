<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
class CourseQuiz extends Model
{
protected $fillable = [
    'title',
    'instructions',
    'settings',
    'questions',
 ];

 protected $casts = [
    'settings' => 'array',
    'questions' => 'array',
 ];

 public function modules(): MorphToMany
 {
    return $this->morphToMany(ModuleItem::class, 'itemable', 'module_items');
 }
}
