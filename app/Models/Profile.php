<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Profile extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'user_id', 'no_kontak', 'alamat', 'no_rekening', 'bank'
    ];

    protected $hidden = [
        'no_kontak',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
