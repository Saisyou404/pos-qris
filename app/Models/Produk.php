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

    // Relasi produk dengan kategori
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi produk dengan detail transaksi
    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }

    // Memastikan stok produk masih cukup untuk jumlah yang diminta
    public function pastikanStokCukup(int $jumlah): void
    {
        // Jika stok lebih kecil dari jumlah yang diminta, tampilkan error
        if ($this->stok < $jumlah) {
            throw new \Exception("Stok produk '{$this->nama}' tidak mencukupi. Tersisa: {$this->stok}");
        }
    }

    // Mengurangi stok produk setelah transaksi berhasil
    public function kurangiStok(int $jumlah): void
    {
        // Cek terlebih dahulu apakah stok mencukupi
        $this->pastikanStokCukup($jumlah);

        // Kurangi stok di database sesuai jumlah transaksi
        $this->decrement('stok', $jumlah);
    }
}