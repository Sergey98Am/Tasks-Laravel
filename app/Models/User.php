<?php

namespace App\Models;

use App\Notifications\PasswordResetNotification;
use App\Notifications\VerifyNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public function getFullNameAttribute() {
//        return ucwords($this->first_name.' '.$this->last_name);
//    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyNotification());
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function boards()
    {
        return $this->belongsToMany('App\Models\Board');
    }

    public function socialAccounts()
    {
        return $this->hasMany('App\Models\SocialAccount');
    }

    public function cards()
    {
        return $this->belongsToMany('App\Models\Card');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment')->where('parent_id', null);
    }
}
