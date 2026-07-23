<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // ← TAMBAHKAN INI

use App\Models\Pengguna;
use App\Models\DetailTransaksi;
use App\Models\PembayaranQris;
use App\Models\CetakStruk;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'pengguna_id',
        'nomor_invoice',
        'tanggal_transaksi',
        'total_pembayaran',
        'uang_diterima',
        'kembalian',
        'metode_pembayaran',
        'status',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_pembayaran'  => 'decimal:2',
        'uang_diterima'     => 'decimal:2',
        'kembalian'         => 'decimal:2',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function pembayaranQris(): HasOne
    {
        return $this->hasOne(PembayaranQris::class, 'transaksi_id');
    }

    public function cetakStruk(): HasOne
    {
        return $this->hasOne(CetakStruk::class, 'transaksi_id');
    }
}