<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ModuleItem extends Model
{
    protected $fillable = [
        'course_module_id',
        'itemable_id',
        'itemable_type',
        'order_index',
    ];

   public function module(): BelongsTo
   {
       return $this->belongsTo(CourseModule::class, 'course_module_id');
   }

public function itemable()
{
     return $this->morphTo();
}

    
}
