<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Kategori;
use App\Models\DetailTransaksi;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';

    protected $fillable = [
        'kategori_id',
        'nama',
        'harga',
        'stok',
        'deskripsi',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }

    /**
     * Pastikan stok masih cukup untuk jumlah yang diminta.
     * Dipakai saat transaksi dibuat (baik tunai maupun QRIS) untuk mencegah
     * kasir memesan produk yang stoknya sudah habis/kurang.
     *
     * @throws \Exception jika stok tidak mencukupi
     */
    public function pastikanStokCukup(int $jumlah): void
    {
        if ($this->stok < $jumlah) {
            throw new \Exception("Stok produk '{$this->nama}' tidak mencukupi. Tersisa: {$this->stok}");
        }
    }

    /**
     * Kurangi stok produk sebanyak $jumlah.
     * Business rule: stok tidak boleh dikurangi melebihi stok yang tersedia.
     *
     * Sebelumnya validasi ini ada di TransaksiController — dipindahkan ke sini
     * supaya aturan "stok tidak boleh minus" konsisten dipakai di mana pun
     * pengurangan stok terjadi (transaksi tunai, callback QRIS, polling status).
     *
     * @throws \Exception jika stok tidak mencukupi
     */
    public function kurangiStok(int $jumlah): void
    {
        $this->pastikanStokCukup($jumlah);
        $this->decrement('stok', $jumlah);
    }
}
