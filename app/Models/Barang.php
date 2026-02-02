<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nup',
        'nama_barang',
        'ketersediaan',
        'waktu_kembali'
    ];

    public $timestamps = false;
}
