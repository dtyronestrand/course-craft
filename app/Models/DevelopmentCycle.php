<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentCycle extends Model
{
  protected $fillable = [
    'name',
    'start_date',
    'end_date',
  ];
  
  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
  ];



  public function courses()
  {
    return $this->hasMany(Course::class);
  }

}
