<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Profile extends Model
{
    use HasFactory;
protected $fillable = [
        'user_id',
        'bio',
        'is_admin',
        'allowed_roles',
        'avatar',
        'title',
    ];

    protected $casts = [
        'allowed_roles' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
