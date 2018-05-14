<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Contracts\Auth\Authenticatable as Authenticable2;// ###stef
// use Illuminate\Auth\Authenticatable as AuthenticableTrait;// ###stef
class User extends Authenticatable
{
    use Notifiable;
    // use AuthenticableTrait; // ###Stef 
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
    public function instagramAccount()
    {
        return $this->hasOne('App\SocialInstagramAccount');
    }
}
