<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
class CoursePage extends Model
{
protected $fillable = [
    'title',
    'content',
 ];

    public function modules(): MorphToMany
    {
        return $this->morphToMany(ModuleItem::class, 'itemable', 'module_items');
    }

}
