<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Cmgmyr\Messenger\Traits\Messagable;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasApiTokens, Messagable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'google_token',
        'google_refresh_token',
        'google_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'google_token_expires_at' => 'datetime',
        ];
    }

    public function courses() : BelongsToMany
    {
        return $this->belongsToMany(Course::class)->withPivot('role');
    }
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
    public function hasGoogleAccess()
    {
        return !empty($this->google_token);
    }
      public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();
    }
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'prticipants')->using(Participant::class)->withTimestamps();
    }
        public function hostedAppointments()
    {
        return $this->hasMany(Appointment::class, 'host_id');
    }

    // Appointments where this user is invited as a guest
    public function guestAppointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_user');
    }
}
