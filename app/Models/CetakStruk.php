<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CetakStruk extends Model
{
    protected $table = 'cetak_struk';

    protected $fillable = [
        'transaksi_id',
        'waktu_cetak',
    ];

    protected $casts = [
        'waktu_cetak' => 'datetime',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
