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
        'kondisi_awal',
        'waktu_pengajuan',
        'waktu_pinjam',
        'waktu_kembali',
        'status',
        'kondisi_kembali',
        'catatan_kondisi',
        'tanggal_jatuh_tempo',
        'approved_by',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'waktu_pengajuan' => 'datetime',
        'waktu_pinjam' => 'datetime',
        'waktu_kembali' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'nomor_bmn', 'nomor_bmn');
    }
}
