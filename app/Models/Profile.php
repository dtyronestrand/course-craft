<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Profile extends Model
{
protected $fillable = [
        'user_id',
        'bio',
        'is_admin',
        'allowed_roles',
    ];

    protected $casts = [
        'allowed_roles' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
