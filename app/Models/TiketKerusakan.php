<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketKerusakan extends Model
{
    protected $table = 'tiket_kerusakan';

    protected $fillable = [
        'nomor_bmn',
        'pelapor',
        'jenis_kerusakan',
        'deskripsi',
        'status',
        'tanggal_lapor'
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
    ];
}
