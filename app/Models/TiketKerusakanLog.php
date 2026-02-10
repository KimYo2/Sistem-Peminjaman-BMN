<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketKerusakanLog extends Model
{
    protected $table = 'tiket_kerusakan_logs';

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'from_status',
        'to_status',
        'note',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
