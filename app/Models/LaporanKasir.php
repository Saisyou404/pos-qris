<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKasir extends Model
{
    use HasFactory;

    protected $table = 'laporan_kasir';

    protected $fillable = [
        'pengguna_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'total_transaksi',
        'total_pendapatan',
        'pendapatan_tunai',
        'pendapatan_qris',
        'kondisi_toko',
        'catatan_kejadian',
        'saran',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'total_pendapatan' => 'float',
        'pendapatan_tunai' => 'float',
        'pendapatan_qris'  => 'float',
    ];

    // Relasi: laporan ini dibuat oleh satu Pengguna (kasir)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // Cek apakah kasir ini sudah submit laporan harian untuk hari ini
    public static function sudahSubmitHariIni(int $kasirId): bool
    {
        return self::where('pengguna_id', $kasirId)
            ->whereDate('tanggal', today())
            ->exists();
    }

    // Ambil laporan hari ini milik kasir (kalau ada)
    public static function laporanHariIni(int $kasirId): ?self
    {
        return self::where('pengguna_id', $kasirId)
            ->whereDate('tanggal', today())
            ->first();
    }

    // Buat laporan harian baru, statistik diambil otomatis dari tabel transaksi
    // (bukan dari input manual kasir), supaya datanya akurat dan tidak bisa dimanipulasi
    public static function buatLaporanHarian(int $kasirId, array $data): self
    {
        if (self::sudahSubmitHariIni($kasirId)) {
            throw new \Exception('Laporan hari ini sudah disubmit sebelumnya.');
        }

        $stats = Transaksi::statistikHarian($kasirId);

        return self::create([
            'pengguna_id'      => $kasirId,
            'tanggal'          => today(),
            'jam_mulai'        => $data['jam_mulai'],
            'jam_selesai'      => $data['jam_selesai'],
            'total_transaksi'  => $stats['total_transaksi'],
            'total_pendapatan' => $stats['total_pendapatan'],
            'pendapatan_tunai' => $stats['tunai'],
            'pendapatan_qris'  => $stats['qris'],
            'kondisi_toko'     => $data['kondisi_toko'],
            'catatan_kejadian' => $data['catatan_kejadian'] ?? null,
            'saran'            => $data['saran'] ?? null,
        ]);
    }
}