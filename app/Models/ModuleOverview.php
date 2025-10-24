<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ModuleOverview extends Model
{
protected $fillable = [
  
        'content',
    ];

  public function moduleItem()
  {
    return $this->morphOne(ModuleItem::class, 'itemable');
  }

  protected static function boot()
  {
    parent::boot();
    
    static::deleting(function ($overview) {
      $overview->moduleItem()->delete();
    });
  }
}
