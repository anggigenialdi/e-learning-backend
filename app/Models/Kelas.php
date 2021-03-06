<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Kelas extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'kursus_id', 'judul', 'posisi'
    ];

    protected $hidden = [
        'id',
    ];

    public function kursus(){
        return $this->belongsTo(Kursus::class);
    }

    public function materi(){
        return $this->hasMany(Materi::class, 'kelas_id');
    }

    public function komentar(){
        return $this->hasMany(Komentar::class, 'kelas_id');
    }

    public function kelas_selesai(){
        return $this->hasMany(Kelas_selesai::class, 'kelas_id');
    }

    public function terakhir_ditonton(){
        return $this->hasMany(Terakhir_ditonton::class, 'kelas_id');
    }

}
