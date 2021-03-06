<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'email','token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password','token'
    ];

    public function user_profile(){
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function kursus_saya(){
        return $this->hasOne(Kursus_saya::class, 'user_id');
    }

    public function komentar(){
        return $this->hasOne(Komentar::class, 'user_id');
    }

    public function transaksi(){
        return $this->hasMany(Transaksi::class, 'user_id');
    }

    public function rating_kursus(){
        return $this->hasMany(Rating_kursus::class, 'user_id');
    }

    public function kursus_aktif(){
        return $this->hasMany(Kursus_aktif::class, 'user_id');
    }

    public function kelas_selesai(){
        return $this->hasMany(Kelas_selesai::class, 'user_id');
    }

    public function terakhir_ditonton(){
        return $this->hasMany(Terakhir_ditonton::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
