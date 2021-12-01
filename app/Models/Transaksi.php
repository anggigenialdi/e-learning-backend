<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Transaksi extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'user_id', 'kursus_id', 'total_price', 'tanggal_pembelian', 'status_transaksi'
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
