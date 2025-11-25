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
  protected $dates = [
    'start_date',
    'end_date',
  ];



  public function courses()
  {
    return $this->hasMany(Course::class);
  }

}
