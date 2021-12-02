<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Rating_kursus extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'user_id', 'kursus_id', 'rating', 'review'
    ];

    protected $hidden = [
        'id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function kursus(){
        return $this->belongsTo(Kursus::class);
    }
}
