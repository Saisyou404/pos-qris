<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\LaporanKasir;

class KasirController extends Controller
{
    // =========================================================
    //  RIWAYAT TRANSAKSI
    // =========================================================

    public function riwayat(Request $request)
    {
        $kasirId   = session('user_id');
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date',   now()->toDateString());
        $status    = $request->get('status',  'all');
        $metode    = $request->get('metode',  'all');

        $query = Transaksi::with(['detailTransaksi.produk'])
            ->where('pengguna_id', $kasirId)
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('tanggal_transaksi', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($metode !== 'all') {
            $query->where('metode_pembayaran', $metode);
        }

        $transaksi      = $query->paginate(15);
        $totalPendapatan = $query->sum('total_pembayaran');

        // Hitung ulang total_pendapatan dari query tanpa paginate
        $totalPendapatan = Transaksi::where('pengguna_id', $kasirId)
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate . ' 23:59:59'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($metode !== 'all', fn($q) => $q->where('metode_pembayaran', $metode))
            ->sum('total_pembayaran');

        return view('kasir.riwayat-transaksi', compact(
            'transaksi',
            'totalPendapatan',
            'startDate',
            'endDate',
            'status',
            'metode'
        ));
    }

    // =========================================================
    //  LAPORAN HARIAN — INPUT
    // =========================================================

    public function laporanInput()
    {
        $kasirId = session('user_id');

        // Cek apakah sudah submit hari ini
        $laporanHariIni = LaporanKasir::where('pengguna_id', $kasirId)
            ->whereDate('tanggal', today())
            ->first();

        $sudahSubmit = (bool) $laporanHariIni;

        // Statistik hari ini dari transaksi
        $statsHariIni = $this->getStatsHariIni($kasirId);

        return view('kasir.laporan-input', compact(
            'sudahSubmit',
            'laporanHariIni',
            'statsHariIni'
        ));
    }

    public function laporanStore(Request $request)
    {
        $kasirId = session('user_id');

        // Cegah double submit
        $sudahAda = LaporanKasir::where('pengguna_id', $kasirId)
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudahAda) {
            return redirect()->route('kasir.laporan.input')
                ->with('error', 'Laporan hari ini sudah disubmit sebelumnya.');
        }

        $request->validate([
            'jam_mulai'  => 'required',
            'jam_selesai'=> 'required',
            'kondisi_toko'=> 'required|in:baik,sedang,buruk',
        ]);

        // Ambil data dari sistem (bukan dari request, agar akurat)
        $stats = $this->getStatsHariIni($kasirId);

        LaporanKasir::create([
            'pengguna_id'       => $kasirId,
            'tanggal'           => today(),
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
            'total_transaksi'   => $stats['total_transaksi'],
            'total_pendapatan'  => $stats['total_pendapatan'],
            'pendapatan_tunai'  => $stats['tunai'],
            'pendapatan_qris'   => $stats['qris'],
            'kondisi_toko'      => $request->kondisi_toko,
            'catatan_kejadian'  => $request->catatan_kejadian,
            'saran'             => $request->saran,
        ]);

        return redirect()->route('kasir.laporan.input')
            ->with('success', 'Laporan harian berhasil disubmit!');
    }

    // =========================================================
    //  LAPORAN HARIAN — RIWAYAT
    // =========================================================

    public function laporanRiwayat(Request $request)
    {
        $kasirId = session('user_id');
        $bulan   = $request->get('bulan', now()->month);
        $tahun   = $request->get('tahun', now()->year);

        $laporan = LaporanKasir::where('pengguna_id', $kasirId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->paginate(12);

        return view('kasir.laporan-riwayat', compact('laporan', 'bulan', 'tahun'));
    }

    // =========================================================
    //  HELPER
    // =========================================================

    private function getStatsHariIni(int $kasirId): array
    {
        $base = Transaksi::where('pengguna_id', $kasirId)
            ->whereDate('tanggal_transaksi', today())
            ->where('status', 'dibayar');

        return [
            'total_transaksi'  => (clone $base)->count(),
            'total_pendapatan' => (clone $base)->sum('total_pembayaran'),
            'tunai'            => (clone $base)->where('metode_pembayaran', 'tunai')->sum('total_pembayaran'),
            'qris'             => (clone $base)->where('metode_pembayaran', 'qris')->sum('total_pembayaran'),
            'total_item'       => \App\Models\DetailTransaksi::whereHas('transaksi', function ($q) use ($kasirId) {
                $q->where('pengguna_id', $kasirId)
                  ->whereDate('tanggal_transaksi', today())
                  ->where('status', 'dibayar');
            })->sum('jumlah'),
        ];
    }
}