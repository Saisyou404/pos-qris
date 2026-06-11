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
        'tanggal'           => 'date',
        'total_pendapatan'  => 'float',
        'pendapatan_tunai'  => 'float',
        'pendapatan_qris'   => 'float',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}