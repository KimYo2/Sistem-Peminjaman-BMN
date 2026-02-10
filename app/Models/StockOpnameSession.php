<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameSession extends Model
{
    public const STATUS_BERJALAN = 'berjalan';
    public const STATUS_SELESAI = 'selesai';

    protected $table = 'stock_opname_sessions';

    protected $fillable = [
        'nama',
        'status',
        'started_by',
        'started_at',
        'finished_at',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function starter()
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    public function items()
    {
        return $this->hasMany(StockOpnameItem::class, 'session_id');
    }
}
