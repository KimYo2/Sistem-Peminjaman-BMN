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

    public function scopeFilter($query, array $filters): void
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('nama_peminjam', 'like', "%{$search}%")
                    ->orWhere('nip_peminjam', 'like', "%{$search}%");
            });
        }
    }

    public function barang()
    {
        // Lazy-load only. Untuk listing gunakan join kode_barang + nup.
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang')
            ->where('nup', $this->nup);
    }

    public function getStatusLabelAttribute(): string
    {
        $map = [
            'menunggu' => 'Menunggu Persetujuan',
            'dipinjam' => 'Sedang Dipinjam',
            'ditolak' => 'Ditolak',
            'dikembalikan' => 'Dikembalikan',
        ];

        $key = strtolower((string) $this->status);

        return $map[$key] ?? 'Selesai';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $map = [
            'menunggu' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
            'dipinjam' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
            'ditolak' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
            'dikembalikan' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
        ];

        $key = strtolower((string) $this->status);

        return $map[$key] ?? 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600';
    }
}
