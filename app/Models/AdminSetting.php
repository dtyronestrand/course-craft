<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
   protected $fillable = [
       'cycle_name',
       'cycle_start',
       'cycle_end',
   ];
}
