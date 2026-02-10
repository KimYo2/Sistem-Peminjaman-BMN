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
        'tanggal_lapor',
        'priority',
        'assigned_to',
        'target_selesai_at',
        'closed_at',
        'admin_notes',
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
        'target_selesai_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public $timestamps = false;

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
