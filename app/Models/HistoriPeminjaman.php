<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriPeminjaman extends Model
{
    protected $table = 'histori_peminjaman';

    protected $fillable = [
        'kode_barang',
        'nup',
        'nip_peminjam',
        'waktu_pinjam',
        'waktu_kembali',
        'status',
    ];

    public $timestamps = false;
}
