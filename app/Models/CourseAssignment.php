<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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

 public function modules(): MorphToMany
 {
    return $this->morphToMany(ModuleItem::class, 'itemable', 'module_items');
 }
}
