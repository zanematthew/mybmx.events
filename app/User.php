<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function schedules()
    {
        return $this->hasMany('App\Schedule');
    }

    public function socialAccount()
    {
        return $this->hasOne('App\SocialAccount');
    }

    public function getAvatarAttribute()
    {
        return $this->socialAccount()->first()->avatar;
    }
}
