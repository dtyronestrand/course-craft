<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
   protected $fillable = [
    'user_id',
    'subject_type',
    'subject_id',
    'action',
    'description',
    'metadata',
   ];

    protected $casts = [
     'metadata' => 'array',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject() : MorphTo
    {
        return $this->morphTo();
    }
}
