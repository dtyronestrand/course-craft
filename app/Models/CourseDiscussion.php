<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseDiscussion extends Model
{
 protected $fillable = [
    'title',
    'is_graded',
    'settings',
    'prompt',
 ];
    protected $casts = [
        'settings' => 'array',
    ];


  
    public function moduleItem()
    {
        return $this->morphOne(ModuleItem::class, 'itemable');
    }


}
