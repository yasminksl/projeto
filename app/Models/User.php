<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
// use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    // use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function setPasswordAttributes($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getProfilePhotoUrlAttribute()
    {
        return asset("storage/images/profiles/{$this->profile_photo_path}");
    }
}
