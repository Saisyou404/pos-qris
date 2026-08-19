<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CetakStruk extends Model
{
    // Nama tabel di database
    protected $table = 'cetak_struk';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'transaksi_id',
        'waktu_cetak',
    ];

    // Konversi otomatis waktu_cetak jadi objek datetime
    protected $casts = [
        'waktu_cetak' => 'datetime',
    ];

    // Relasi: satu CetakStruk dimiliki oleh satu Transaksi
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}