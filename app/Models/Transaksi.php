<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

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

    // =========================================================
    //  RELASI
    // =========================================================

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

    // =========================================================
    //  SCOPE
    // =========================================================

    public function scopeDibayar(Builder $query): Builder
    {
        return $query->where('status', 'dibayar');
    }

    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('tanggal_transaksi', today());
    }

    public function scopeMilikKasir(Builder $query, int $kasirId): Builder
    {
        return $query->where('pengguna_id', $kasirId);
    }

    // =========================================================
    //  BUSINESS LOGIC — dulu ada di TransaksiController
    // =========================================================

    /**
     * Buat nomor invoice unik untuk transaksi baru.
     */
    public static function buatNomorInvoice(): string
    {
        return 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }

    /**
     * Hitung kembalian untuk pembayaran tunai.
     *
     * @throws \Exception jika uang diterima kurang dari total pembayaran
     */
    public static function hitungKembalian(float $total, float $uangDiterima): float
    {
        if ($uangDiterima < $total) {
            throw new \Exception('Uang yang diterima kurang dari total pembayaran.');
        }

        return $uangDiterima - $total;
    }

    /**
     * Statistik transaksi hari ini yang sudah dibayar.
     * $kasirId = null  -> statistik gabungan seluruh kasir (untuk dashboard admin)
     * $kasirId = angka -> statistik milik satu kasir saja (untuk dashboard/laporan kasir)
     */
    public static function statistikHarian(?int $kasirId = null): array
    {
        $query = self::query()->hariIni()->dibayar();

        if ($kasirId !== null) {
            $query->milikKasir($kasirId);
        }

        $totalPendapatan = (clone $query)->sum('total_pembayaran');
        $totalTransaksi  = (clone $query)->count();

        $tunai = (clone $query)->where('metode_pembayaran', 'tunai')->sum('total_pembayaran');
        $qris  = (clone $query)->where('metode_pembayaran', 'qris')->sum('total_pembayaran');

        $totalItem = DetailTransaksi::whereHas('transaksi', function ($q) use ($kasirId) {
            $q->hariIni()->dibayar();
            if ($kasirId !== null) {
                $q->milikKasir($kasirId);
            }
        })->sum('jumlah');

        return [
            'total_transaksi'  => $totalTransaksi,
            'total_pendapatan' => $totalPendapatan,
            'rata_rata'        => $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0,
            'tunai'            => $tunai,
            'qris'             => $qris,
            'total_item'       => $totalItem,
        ];
    }

    /**
     * Terapkan hasil status transaksi dari Midtrans ke transaksi ini:
     * update status, kurangi stok produk, dan update record pembayaran QRIS.
     *
     * Dipanggil baik dari callback webhook Midtrans maupun dari polling
     * checkStatus(), supaya logikanya tidak duplikat di dua tempat.
     */
    public function terapkanStatusMidtrans(string $statusMidtrans, ?array $dataCallback = null): void
    {
        if (in_array($statusMidtrans, ['settlement', 'capture'])) {

            if ($this->status === 'dibayar') {
                return; // sudah pernah diproses sebelumnya, jangan diulang
            }

            $this->status = 'dibayar';
            $this->save();

            foreach ($this->detailTransaksi as $detail) {
                $detail->produk?->kurangiStok($detail->jumlah);
            }

            $this->pembayaranQris?->tandaiBerhasil($dataCallback);

        } elseif ($statusMidtrans === 'pending') {

            if (!in_array($this->status, ['dibayar', 'dibatalkan'])) {
                $this->status = 'pending';
                $this->save();
            }

        } elseif (in_array($statusMidtrans, ['expire', 'cancel', 'deny'])) {

            if ($this->status !== 'dibayar') {
                $this->status = 'dibatalkan';
                $this->save();

                $this->pembayaranQris?->tandaiKedaluwarsa($dataCallback);
            }
        }
    }
}
