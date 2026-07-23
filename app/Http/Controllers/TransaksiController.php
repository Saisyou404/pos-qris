<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\PembayaranQris;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class TransaksiController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function create()
    {
        $produks = Produk::with('kategori')
                        ->where('stok', '>', 0)
                        ->orderBy('nama', 'asc')
                        ->get();

        $kategoris = Kategori::orderBy('nama', 'asc')->get();

        $lastTransaksi  = Transaksi::latest('id')->first();
        $nextNumber     = $lastTransaksi ? $lastTransaksi->id + 1 : 1;
        $nomorTransaksi = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('kasir.transaksi', compact('produks', 'kategoris', 'nomorTransaksi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'                  => 'required|array|min:1',
            'items.*.produk_id'      => 'required|exists:produk,id',
            'items.*.jumlah'         => 'required|integer|min:1',
            'items.*.harga'          => 'required|numeric|min:0',
            'total'                  => 'required|numeric|min:0',
            'metode_pembayaran'      => 'required|in:tunai,qris',
            // Untuk tunai, uang_diterima WAJIB diisi dan tidak boleh kurang dari
            // total. Sebelumnya ini hanya dicek di JS (bisa dilewati lewat
            // request langsung ke endpoint ini), sekarang dipaksa di server juga.
            'uang_diterima'          => 'required_if:metode_pembayaran,tunai|nullable|numeric|gte:total',
        ]);

        DB::beginTransaction();

        try {
            $nomorInvoice = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

            $isTunai      = $validated['metode_pembayaran'] === 'tunai';
            $uangDiterima = $isTunai ? $validated['uang_diterima'] : null;
            $kembalian    = $isTunai ? $uangDiterima - $validated['total'] : null;

            $transaksi = Transaksi::create([
                'pengguna_id'       => session('user_id'),
                'nomor_invoice'     => $nomorInvoice,
                'tanggal_transaksi' => now(),
                'total_pembayaran'  => $validated['total'],
                'uang_diterima'     => $uangDiterima,
                'kembalian'         => $kembalian,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status'            => $validated['metode_pembayaran'] === 'qris' ? 'pending' : 'dibayar',
            ]);

            foreach ($validated['items'] as $item) {
                $produk = Produk::lockForUpdate()->find($item['produk_id']);

                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception("Stok produk '{$produk->nama}' tidak mencukupi. Tersisa: {$produk->stok}");
                }

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $item['produk_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $item['jumlah'] * $item['harga'],
                ]);

                if ($validated['metode_pembayaran'] === 'tunai') {
                    $produk->decrement('stok', $item['jumlah']);
                }
            }

            $responseData = [
                'transaksi_id'  => $transaksi->id,
                'nomor_invoice' => $nomorInvoice,
                'total'         => $validated['total'],
            ];

            if ($validated['metode_pembayaran'] === 'qris') {
                $params = [
                    'transaction_details' => [
                        'order_id'     => $nomorInvoice,
                        'gross_amount' => (int) $validated['total'],
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);

                PembayaranQris::create([
                    'transaksi_id' => $transaksi->id,
                    'invoice_qris' => $nomorInvoice,
                    'qris_string'  => $snapToken,
                    'nominal'      => $validated['total'],
                    'status'       => 'menunggu',
                ]);

                $responseData['snap_token'] = $snapToken;
                $responseData['qris_mode']  = true;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data'    => $responseData,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed    = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if (!hash_equals($hashed, (string) $request->signature_key)) {
            return response()->json(['status' => 'invalid signature'], 403);
        }

        return DB::transaction(function () use ($request) {

            $transaksi = Transaksi::where('nomor_invoice', $request->order_id)
                                  ->with('detailTransaksi')
                                  ->lockForUpdate()
                                  ->first();

            if (!$transaksi) {
                return response()->json(['status' => 'transaksi tidak ditemukan'], 404);
            }

            if ($request->transaction_status === 'settlement' || $request->transaction_status === 'capture') {

                if ($transaksi->status === 'dibayar') {
                    return response()->json(['status' => 'ok', 'note' => 'sudah diproses sebelumnya']);
                }

                $transaksi->status = 'dibayar';
                $transaksi->save();

                foreach ($transaksi->detailTransaksi as $detail) {
                    Produk::where('id', $detail->produk_id)
                          ->decrement('stok', $detail->jumlah);
                }

                $qris = PembayaranQris::where('transaksi_id', $transaksi->id)->first();
                if ($qris) {
                    $qris->status         = 'berhasil';
                    $qris->waktu_callback = now();
                    $qris->data_callback  = $request->all();
                    $qris->save();
                }

            } elseif ($request->transaction_status === 'pending') {

                if ($transaksi->status !== 'dibayar' && $transaksi->status !== 'dibatalkan') {
                    $transaksi->status = 'pending';
                    $transaksi->save();
                }

            } elseif (in_array($request->transaction_status, ['expire', 'cancel', 'deny'])) {

                if ($transaksi->status !== 'dibayar') {
                    $transaksi->status = 'dibatalkan';
                    $transaksi->save();

                    $qris = PembayaranQris::where('transaksi_id', $transaksi->id)->first();
                    if ($qris) {
                        $qris->status        = 'kedaluwarsa';
                        $qris->data_callback = $request->all();
                        $qris->save();
                    }
                }
            }

            return response()->json(['status' => 'ok']);
        });
    }

    public function checkStatus($invoiceNumber)
    {
        $transaksi = Transaksi::where('nomor_invoice', $invoiceNumber)
                              ->where('pengguna_id', session('user_id'))
                              ->with('pembayaranQris')
                              ->first();

        if (!$transaksi) {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        try {
            $statusMidtrans = \Midtrans\Transaction::status($invoiceNumber);

            if (in_array($statusMidtrans->transaction_status, ['settlement', 'capture'])) {
                if ($transaksi->status === 'pending') {
                    $transaksi->status = 'dibayar';
                    $transaksi->save();

                    foreach ($transaksi->detailTransaksi as $detail) {
                        Produk::where('id', $detail->produk_id)
                              ->decrement('stok', $detail->jumlah);
                    }

                    $qris = $transaksi->pembayaranQris;
                    if ($qris) {
                        $qris->status         = 'berhasil';
                        $qris->waktu_callback = now();
                        $qris->save();
                    }
                }
            } elseif (in_array($statusMidtrans->transaction_status, ['expire', 'cancel', 'deny'])) {
                $transaksi->status = 'dibatalkan';
                $transaksi->save();
            }

            return response()->json([
                'success' => true,
                'status'  => $transaksi->fresh()->status,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'status'  => $transaksi->status,
            ]);
        }
    }

    public function cetakStruk($invoice)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'pengguna'])
            ->where('nomor_invoice', $invoice)
            ->where('pengguna_id', session('user_id'))
            ->firstOrFail();

        return view('kasir.struk', compact('transaksi'));
    }
}