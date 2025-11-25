<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
   protected $fillable = [
       'name',
       'template_days_offset',
   ];

public function courseDeliverables()
{
    return $this->hasMany(CourseDeliverable::class);}
}
