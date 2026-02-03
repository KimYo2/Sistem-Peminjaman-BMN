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
        'pic_user_id',
        'peminjam_terakhir',
        'waktu_pinjam',
        'waktu_kembali'
    ];

    public $timestamps = false;

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }
}
