<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Course extends Model
{
    protected $fillable = [
        'prefix',
        'number',
        'title',

    ] ;



    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function modules() : HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function objectives() : HasMany
    {
        return $this->hasMany(CourseObjective::class);
    }
}
