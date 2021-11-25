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

    protected $hidden = [
        'id',
    ];

    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }

}
