<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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

    public function modules(): MorphToMany
    {
        return $this->morphToMany(ModuleItem::class, 'itemable', 'module_items');
    }
}
