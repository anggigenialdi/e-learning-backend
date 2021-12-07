<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Kelas_selesai extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'user_id', 'kelas_id','materi_id','kursus_id'
    ];

    // protected $hidden = [
    //     'id',
    // ];
    public function materi(){
        return $this->belongsTo(Materi::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }

}
