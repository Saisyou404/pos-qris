<?php

namespace App\Http\Controllers;

use App\Models\LaporanKasir;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class LaporanKasirController extends Controller
{
    // Daftar laporan harian dari semua kasir (bisa difilter per kasir & tanggal)
    public function index(Request $request)
    {
        $kasirId   = $request->input('kasir_id', 'all');
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));

        $query = LaporanKasir::with('pengguna')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($kasirId !== 'all') {
            $query->where('pengguna_id', $kasirId);
        }

        $laporan = $query->orderByDesc('tanggal')->orderByDesc('created_at')->paginate(12);

        // Cuma tampilkan kasir yang aktif di dropdown filter
        $kasirs = Pengguna::where('peran', 'kasir')->orderBy('nama')->get();

        // Ringkasan periode terpilih (dihitung dari query yang sama, tanpa filter kasir
        // supaya total selalu utuh kalau nanti mau ditampilkan terpisah dari filter kasir)
        $totalLaporan     = (clone $query)->count();
        $totalPendapatan  = (clone $query)->sum('total_pendapatan');
        $adaKendala       = (clone $query)->whereNotNull('catatan_kejadian')->where('catatan_kejadian', '!=', '')->count();

        return view('admin.laporan-kasir.index', compact(
            'laporan', 'kasirs', 'kasirId', 'startDate', 'endDate',
            'totalLaporan', 'totalPendapatan', 'adaKendala'
        ));
    }
}