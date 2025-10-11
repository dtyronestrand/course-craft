<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CourseObjective extends Model
{
    protected $fillable=[
        'number',
        'objective',
    ];

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
