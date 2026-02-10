<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $table = 'waitlists';

    public $timestamps = false;

    protected $fillable = [
        'kode_barang',
        'nup',
        'nip_peminjam',
        'status',
        'requested_at',
        'notified_at',
        'fulfilled_at',
        'cancelled_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'notified_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
}
