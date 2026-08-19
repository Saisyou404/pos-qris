<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\LaporanKasir;

class KasirController extends Controller
{
    //  RIWAYAT TRANSAKSI
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
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when($metode !== 'all', fn ($q) => $q->where('metode_pembayaran', $metode))
            ->orderBy('tanggal_transaksi', 'desc');

        $totalPendapatan = (clone $query)->sum('total_pembayaran');
        $transaksi       = $query->paginate(15);

        return view('kasir.riwayat-transaksi', compact(
            'transaksi',
            'totalPendapatan',
            'startDate',
            'endDate',
            'status',
            'metode'
        ));
    }

    //  LAPORAN HARIAN — INPUT
    public function laporanInput()
    {
        $kasirId = session('user_id');

        $laporanHariIni = LaporanKasir::laporanHariIni($kasirId);
        $sudahSubmit    = (bool) $laporanHariIni;
        $statsHariIni   = Transaksi::statistikHarian($kasirId);

        return view('kasir.laporan-input', compact(
            'sudahSubmit',
            'laporanHariIni',
            'statsHariIni'
        ));
    }

    public function laporanStore(Request $request)
    {
        $kasirId = session('user_id');

        $request->validate([
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required',
            'kondisi_toko' => 'required|in:baik,sedang,buruk',
        ]);

        try {
            LaporanKasir::buatLaporanHarian($kasirId, $request->only([
                'jam_mulai', 'jam_selesai', 'kondisi_toko', 'catatan_kejadian', 'saran',
            ]));

            return redirect()->route('kasir.laporan.input')
                ->with('success', 'Laporan harian berhasil disubmit!');

        } catch (\Exception $e) {
            return redirect()->route('kasir.laporan.input')
                ->with('error', $e->getMessage());
        }
    }

    //  LAPORAN HARIAN — RIWAYAT
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
}
