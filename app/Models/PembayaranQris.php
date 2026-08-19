<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranQris extends Model
{
    protected $table = 'pembayaran_qris';

    protected $fillable = [
        'transaksi_id',
        'invoice_qris',
        'qris_string',
        'qris_gambar',
        'nominal',
        'status',
        'waktu_callback',
        'data_callback',
    ];

    protected $casts = [
        'data_callback'  => 'array',
        'waktu_callback' => 'datetime',
    ];

    // Relasi: satu pembayaran QRIS punya satu Transaksi
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    // Tandai pembayaran berhasil
    public function tandaiBerhasil(?array $dataCallback = null): void
    {
        $this->status = 'berhasil';
        $this->waktu_callback = now();

        if ($dataCallback !== null) {
            $this->data_callback = $dataCallback;
        }

        $this->save();
    }

    // Tandai pembayaran kedaluwarsa/gagal
    public function tandaiKedaluwarsa(?array $dataCallback = null): void
    {
        $this->status = 'kedaluwarsa';

        if ($dataCallback !== null) {
            $this->data_callback = $dataCallback;
        }

        $this->save();
    }
}