<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

 public function modules()
 {
    return $this->mporhOne(ModuleItem::class, 'itemable', 'module_items');
 }

       protected static function boot()
  {
    parent::boot();
    
    static::deleting(function ($quiz) {
      $quiz->moduleItem()->delete();
    });
  }
}
