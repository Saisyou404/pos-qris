<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Produk;
use App\Models\Pengguna;

class DashboardController extends Controller
{
    // Redirect otomatis berdasarkan role
    public function index()
    {
        $role = session('user_role');

        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($role === 'kasir') {
            return redirect('/kasir/dashboard');
        }

        return redirect('/login');
    }

    // Dashboard Admin
    public function admin()
    {
        // Hitung statistik untuk admin (semua kasir, hanya transaksi dibayar)
        $baseDashboard = Transaksi::whereDate('tanggal_transaksi', today())->where('status', 'dibayar');
        $totalPenjualan = (clone $baseDashboard)->sum('total_pembayaran');
        $totalTransaksi = (clone $baseDashboard)->count();
        $totalProduk = Produk::count();
        $totalPengguna = Pengguna::where('peran', 'kasir')->count();

        // Ambil transaksi terbaru
        $transaksiTerbaru = Transaksi::with('pengguna')
                                    ->orderBy('tanggal_transaksi', 'desc')
                                    ->limit(10)
                                    ->get();
        
        return view('dashboard.admin', compact(
            'totalPenjualan',
            'totalTransaksi',
            'totalProduk',
            'totalPengguna',
            'transaksiTerbaru'
        ));
    }

    // Dashboard Kasir
    public function kasir()
    {
        // Ambil data kasir yang sedang login
        $kasirId = session('user_id');
        $kasirName = session('user_name');
        
        // Hitung statistik hari ini (hanya transaksi dibayar milik kasir ini)
        $baseKasir = Transaksi::where('pengguna_id', $kasirId)
                               ->whereDate('tanggal_transaksi', today())
                               ->where('status', 'dibayar');

        $totalPenjualan = (clone $baseKasir)->sum('total_pembayaran');
        $totalTransaksi = (clone $baseKasir)->count();

        // Hitung rata-rata transaksi
        $rataTransaksi = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;

        // Cek status laporan hari ini
        $laporanHariIni = \App\Models\LaporanKasir::where('pengguna_id', $kasirId)
                                                   ->whereDate('tanggal', today())
                                                   ->first();
        $statusLaporan = $laporanHariIni ? 'Sudah Submit' : 'Belum Submit';

        // Ambil transaksi terakhir (5 transaksi)
        $transaksiTerakhir = Transaksi::where('pengguna_id', $kasirId)
                                      ->whereDate('tanggal_transaksi', today())
                                      ->with('detailTransaksi')
                                      ->orderBy('tanggal_transaksi', 'desc')
                                      ->limit(5)
                                      ->get();

        // Hitung breakdown per metode pembayaran (hanya transaksi dibayar)
        $tunai = (clone $baseKasir)->where('metode_pembayaran', 'tunai')->sum('total_pembayaran');
        $qris  = (clone $baseKasir)->where('metode_pembayaran', 'qris')->sum('total_pembayaran');

        // Set transfer = 0 karena di database hanya ada tunai & qris
        $transfer = 0;
        
        return view('dashboard.kasir', compact(
            'kasirName',
            'totalPenjualan',
            'totalTransaksi',
            'rataTransaksi',
            'statusLaporan',
            'transaksiTerakhir',
            'tunai',
            'transfer',
            'qris'
        ));
    }
}