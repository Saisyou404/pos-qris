<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: hari ini
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        // Parse dates
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Total transaksi
        $totalTransaksi = Transaksi::whereBetween('tanggal_transaksi', [$start, $end])->count();

        // Total pendapatan
        $totalPendapatan = Transaksi::whereBetween('tanggal_transaksi', [$start, $end])
            ->where('status', 'success')
            ->sum('total_pembayaran');

        // Total produk terjual
        $totalProdukTerjual = DetailTransaksi::whereHas('transaksi', function($query) use ($start, $end) {
            $query->whereBetween('tanggal_transaksi', [$start, $end])
                  ->where('status', 'success');
        })->sum('jumlah');

        // Top 5 produk terlaris
        $topProduk = DetailTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_terjual'), DB::raw('SUM(subtotal) as total_pendapatan'))
            ->whereHas('transaksi', function($query) use ($start, $end) {
                $query->whereBetween('tanggal_transaksi', [$start, $end])
                      ->where('status', 'success');
            })
            ->groupBy('produk_id')
            ->orderBy('total_terjual', 'desc')
            ->with('produk')
            ->limit(5)
            ->get();

        // Daftar transaksi
        $transaksi = Transaksi::with(['pengguna', 'detailTransaksi.produk'])
            ->whereBetween('tanggal_transaksi', [$start, $end])
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10);

        // Data grafik penjualan (7 hari terakhir)
        $grafikData = Transaksi::selectRaw('DATE(tanggal_transaksi) as tanggal, SUM(total_pembayaran) as total')
            ->where('status', 'success')
            ->whereBetween('tanggal_transaksi', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Isi tanggal yang kosong dengan 0
        $grafikLabels = [];
        $grafikValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $grafikLabels[] = Carbon::parse($date)->format('d M');
            
            $found = $grafikData->firstWhere('tanggal', $date);
            $grafikValues[] = $found ? $found->total : 0;
        }

        return view('admin.laporan.index', compact(
            'totalTransaksi',
            'totalPendapatan',
            'totalProdukTerjual',
            'topProduk',
            'transaksi',
            'startDate',
            'endDate',
            'grafikLabels',
            'grafikValues'
        ));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['pengguna', 'detailTransaksi.produk'])->findOrFail($id);
        return view('admin.laporan.show', compact('transaksi'));
    }
}