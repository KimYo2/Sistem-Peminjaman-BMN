<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameItem extends Model
{
    public const STATUS_MISSING = 'missing';
    public const STATUS_FOUND = 'found';

    protected $table = 'stock_opname_items';

    protected $fillable = [
        'session_id',
        'kode_barang',
        'nup',
        'status',
        'expected_kondisi',
        'actual_kondisi',
        'scanned_at',
        'scanned_by',
        'notes',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(StockOpnameSession::class, 'session_id');
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function getNomorBmnAttribute(): string
    {
        return $this->kode_barang . '-' . $this->nup;
    }
}
