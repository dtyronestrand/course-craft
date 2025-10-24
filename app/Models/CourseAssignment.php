<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CourseAssignment extends Model
{
protected $fillable = [
    'title',
    'purpose',
    'criteria',
    'settings',
 ];

 protected $casts = [
    'settings' => 'array',
 ];

 public function moduleItem()
 {
    return $this->morphOne(ModuleItem::class, 'itemable');
 }

 protected static function boot()
 {
    parent::boot();
    
    static::deleting(function ($assignment) {
      $assignment->moduleItem()->delete();
    });
 }
}
