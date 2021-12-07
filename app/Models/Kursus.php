<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Kursus extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'instruktur_id', 'judul_kursus', 'foto', 'harga_kursus', 'tipe_kursus'
    ];

    protected $hidden = [
        'id',
    ];

    public function instruktur(){
        return $this->belongsTo(Instruktur::class);
    }

    public function kelas(){
        return $this->hasMany(Kelas::class, 'kursus_id');
    }

    public function kursus_Saya(){
        return $this->hasMany(Kursus_saya::class, 'kursus_id');
    }

    public function komentar(){
        return $this->hasMany(Komentar::class, 'kursus_id');
    }

    public function transaksi(){
        return $this->hasMany(Transaksi::class, 'kursus_id');
    }

    public function rating_kursus(){
        return $this->hasMany(Rating_kursus::class, 'kursus_id');
    }
    
    public function kursus_aktif(){
        return $this->hasMany(Kursus_aktif::class, 'kursus_id');
    }
    public function terakhir_ditonton(){
        return $this->hasMany(Terakhir_ditonton::class, 'kursus_id');
    }

    

}
