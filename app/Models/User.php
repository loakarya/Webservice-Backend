<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // Rest omitted for brevity

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
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'address',
        'zip_code',
        'city',
        'province',
        'country'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function devices() {
        return $this->hasMany('App\Device');
    }

    public function faqs() {
        return $this->hasMany('App\FAQ');
    }

    public function articles() {
        return $this->hasMany('App\Models\Article');
    }

    public function articleCategories() {
        return $this->hasMany('App\Models\ArticleCategory');
    }

    public function products() {
        return $this->hasMany('App\Product');
    }

    public function forms() {
        return $this->hasMany('App\Form');
    }

    public function testimonies() {
        return $this->hasMany('App\Testimony');
    }

    public function devicetypes() {
        return $this->hasMany('App\DeviceType');
    }

    public function token() {
        return $this->hasOne('App\Token');
    }
}
