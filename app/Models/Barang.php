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

    public function scopeFilter($query, array $filters): void
    {
        if (!empty($filters['ketersediaan'])) {
            $query->where('ketersediaan', $filters['ketersediaan']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%")
                    ->orWhere('peminjam_terakhir', 'like', "%{$search}%");
            });
        }
    }

    private function normalizedKondisi(): string
    {
        $value = strtolower(trim((string) $this->kondisi_terakhir));
        $value = str_replace(' ', '_', $value);

        return $value === '' ? 'baik' : $value;
    }

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->normalizedKondisi()) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => 'Tidak Diketahui',
        };
    }

    public function getKondisiBadgeClassAttribute(): string
    {
        return match ($this->normalizedKondisi()) {
            'baik' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'rusak_ringan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'rusak_berat' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
        };
    }

    public function getKondisiDotClassAttribute(): string
    {
        return match ($this->normalizedKondisi()) {
            'baik' => 'bg-green-500',
            'rusak_ringan' => 'bg-yellow-500',
            'rusak_berat' => 'bg-red-500',
            default => 'bg-slate-400',
        };
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }
}
