<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['host_id', 'subject', 'start_time', 'end_time', 'notes'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // The organizer
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }
    
    // The guests (Many-to-Many)
    public function guests()
    {
        return $this->belongsToMany(User::class, 'appointment_user');
    }
}
