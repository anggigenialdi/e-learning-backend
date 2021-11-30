<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Komentar extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'kursus_id','kelas_id','user_id', 'materi_id', 'isi_komentar'
    ];

    protected $hidden = [
        'id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function materi(){
        return $this->belongsTo(Materi::class);
    }

}
