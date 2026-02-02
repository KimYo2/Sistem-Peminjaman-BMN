<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nup',
        'brand',
        'tipe',
        'kondisi_terakhir',
        'nama_barang',
        'ketersediaan',
        'peminjam_terakhir',
        'waktu_pinjam',
        'waktu_kembali'
    ];

    public $timestamps = false;
}
