<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\PembayaranQris;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class TransaksiController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function create()
    {
        $produks = Produk::with('kategori')
                        ->where('stok', '>', 0)
                        ->orderBy('nama', 'asc')
                        ->get();
        
        $kategoris = Kategori::orderBy('nama', 'asc')->get();
        
        $lastTransaksi = Transaksi::latest('id')->first();
        $nextNumber = $lastTransaksi ? $lastTransaksi->id + 1 : 1;
        $nomorTransaksi = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        return view('kasir.transaksi', compact('produks', 'kategoris', 'nomorTransaksi'));
    }
    
    public function store(Request $request)
{
    $validated = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.produk_id' => 'required|exists:produk,id',
        'items.*.jumlah' => 'required|integer|min:1',
        'items.*.harga' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'metode_pembayaran' => 'required|in:tunai,qris',
        'uang_diterima' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $nomorInvoice = 'INV-' . time();

        $transaksi = Transaksi::create([
            'pengguna_id' => session('user_id'),
            'nomor_invoice' => $nomorInvoice,
            'tanggal_transaksi' => now(),
            'total_pembayaran' => $validated['total'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status' => $validated['metode_pembayaran'] === 'qris' ? 'pending' : 'dibayar',
        ]);

        foreach ($validated['items'] as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga'],
                'subtotal' => $item['jumlah'] * $item['harga'],
            ]);

            $produk = Produk::find($item['produk_id']);
            $produk->stok -= $item['jumlah'];
            $produk->save();
        }

        $responseData = [
            'transaksi_id' => $transaksi->id,
            'nomor_invoice' => $nomorInvoice,
            'total' => $validated['total'],
        ];

        // ================= QRIS =================
        if ($validated['metode_pembayaran'] == 'qris') {

    $params = [
        'transaction_details' => [
            'order_id' => $nomorInvoice,
            'gross_amount' => (int) $validated['total'],
        ],
    ];

    $snapToken = Snap::getSnapToken($params);

    PembayaranQris::create([
        'transaksi_id' => $transaksi->id,
        'invoice_qris' => $nomorInvoice,
        'qris_string' => $snapToken,
        'nominal' => $validated['total'],
        'status' => 'menunggu',
    ]);

    $responseData['snap_token'] = $snapToken;
    $responseData['qris_mode'] = true;
}


        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan',
            'data' => $responseData,
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
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            $transaksi = Transaksi::where('nomor_invoice', $request->order_id)->first();
            
            if ($transaksi) {
                if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
                    $transaksi->status = 'dibayar';
                    $transaksi->save();
                    
                    $qris = PembayaranQris::where('transaksi_id', $transaksi->id)->first();
                    if ($qris) {
                        $qris->status = 'berhasil';
                        $qris->waktu_callback = now();
                        $qris->data_callback = json_encode($request->all());
                        $qris->save();
                    }
                } elseif ($request->transaction_status == 'pending') {
                    $transaksi->status = 'pending';
                    $transaksi->save();
                } elseif ($request->transaction_status == 'expire') {
                    $transaksi->status = 'dibatalkan';
                    $transaksi->save();
                    
                    $qris = PembayaranQris::where('transaksi_id', $transaksi->id)->first();
                    if ($qris) {
                        $qris->status = 'kedaluwarsa';
                        $qris->save();
                    }
                }
            }
            
            return response()->json(['status' => 'success']);
        }
        
        return response()->json(['status' => 'invalid signature'], 403);
    }
    
    public function checkStatus($invoiceNumber)
    {
        $transaksi = Transaksi::where('nomor_invoice', $invoiceNumber)
                              ->with('pembayaranQris')
                              ->first();
        
        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'status' => $transaksi->status,
            'qris_status' => $transaksi->pembayaranQris ? $transaksi->pembayaranQris->status : null
        ]);
    }
}