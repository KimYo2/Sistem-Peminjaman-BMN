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
        'nama_peminjam',
        'waktu_pinjam',
        'waktu_kembali',
        'status',
    ];

    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'nomor_bmn', 'nomor_bmn');
    }
}
