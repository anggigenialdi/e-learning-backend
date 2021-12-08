<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Materi extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'kelas_id', 'judul', 'deskripsi', 'link_video', 'posisi', 'materi_sebelumnya', 'materi_selanjutnya'
    ];


    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }
    public function kelas_selesai(){
        return $this->hasMany(Kelas_selesai::class, 'materi_id');
    }

    public function komentar(){
        return $this->hasMany(Komentar::class, 'materi_id');
    }

    public function terakhir_ditonton(){
        return $this->hasMany(Terakhir_ditonton::class, 'materi_id');
    }

}
