<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Pengguna;
use App\Models\LaporanKasir;

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
        $stats = Transaksi::statistikHarian(); // null = gabungan semua kasir

        $totalProduk   = Produk::count();
        $totalPengguna = Pengguna::where('peran', 'kasir')->count();

        $transaksiTerbaru = Transaksi::with('pengguna')
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.admin', [
            'totalPenjualan'   => $stats['total_pendapatan'],
            'totalTransaksi'   => $stats['total_transaksi'],
            'totalProduk'      => $totalProduk,
            'totalPengguna'    => $totalPengguna,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }

    // Dashboard Kasir
    public function kasir()
    {
        $kasirId   = session('user_id');
        $kasirName = session('user_name');

        $stats = Transaksi::statistikHarian($kasirId);

        $statusLaporan = LaporanKasir::sudahSubmitHariIni($kasirId) ? 'Sudah Submit' : 'Belum Submit';

        $transaksiTerakhir = Transaksi::milikKasir($kasirId)
            ->hariIni()
            ->with('detailTransaksi')
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.kasir', [
            'kasirName'         => $kasirName,
            'totalPenjualan'    => $stats['total_pendapatan'],
            'totalTransaksi'    => $stats['total_transaksi'],
            'rataTransaksi'     => $stats['rata_rata'],
            'statusLaporan'     => $statusLaporan,
            'transaksiTerakhir' => $transaksiTerakhir,
            'tunai'             => $stats['tunai'],
            'qris'              => $stats['qris'],
            'transfer'          => 0, // di database hanya ada tunai & qris
        ]);
    }
}
