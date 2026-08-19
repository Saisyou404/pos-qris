<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\PembayaranQris;
use App\Models\CetakStruk;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    protected MidtransService $midtrans;

    // Menerima MidtransService lewat constructor agar seluruh fungsi di Controller ini bisa memanggil Midtrans tanpa membuat objek baru berulang kali
    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    // Menampilkan halaman transaksi kasir, berisi daftar produk yang stoknya masih tersedia dan preview nomor transaksi berikutnya
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

    // Menyimpan transaksi baru beserta item-itemnya, mengurangi stok (untuk tunai), dan membuat Snap Token QRIS (untuk QRIS) dalam satu database transaction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'                  => 'required|array|min:1',
            'items.*.produk_id'      => 'required|exists:produk,id',
            'items.*.jumlah'         => 'required|integer|min:1',
            'items.*.harga'          => 'required|numeric|min:0',
            'total'                  => 'required|numeric|min:0',
            'metode_pembayaran'      => 'required|in:tunai,qris',
            'uang_diterima'          => 'required_if:metode_pembayaran,tunai|nullable|numeric|gte:total',
        ]);

        DB::beginTransaction();

        try {
            $isTunai = $validated['metode_pembayaran'] === 'tunai';

            $transaksi = Transaksi::create([
                'pengguna_id'       => session('user_id'),
                'nomor_invoice'     => Transaksi::buatNomorInvoice(),
                'tanggal_transaksi' => now(),
                'total_pembayaran'  => $validated['total'],
                'uang_diterima'     => $isTunai ? $validated['uang_diterima'] : null,
                'kembalian'         => $isTunai ? Transaksi::hitungKembalian($validated['total'], $validated['uang_diterima']) : null,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status'            => $isTunai ? 'dibayar' : 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                $produk = Produk::lockForUpdate()->find($item['produk_id']);

                $produk->pastikanStokCukup($item['jumlah']);

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $item['produk_id'],
                    'jumlah'       => $item['jumlah'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $item['jumlah'] * $item['harga'],
                ]);

                if ($isTunai) {
                    $produk->kurangiStok($item['jumlah']);
                }
            }

            $responseData = [
                'transaksi_id'  => $transaksi->id,
                'nomor_invoice' => $transaksi->nomor_invoice,
                'total'         => $validated['total'],
            ];

            if (!$isTunai) {
                $snapToken = $this->midtrans->buatSnapToken($transaksi->nomor_invoice, (int) $validated['total']);

                PembayaranQris::create([
                    'transaksi_id' => $transaksi->id,
                    'invoice_qris' => $transaksi->nomor_invoice,
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

    // Endpoint webhook yang dipanggil server Midtrans untuk memperbarui status pembayaran QRIS; signature diverifikasi dulu sebelum status transaksi diproses
    public function callback(Request $request)
    {
        $signatureValid = $this->midtrans->verifikasiSignature(
            $request->order_id,
            $request->status_code,
            $request->gross_amount,
            (string) $request->signature_key
        );

        if (!$signatureValid) {
            return response()->json(['status' => 'invalid signature'], 403);
        }

        return DB::transaction(function () use ($request) {
            $transaksi = Transaksi::where('nomor_invoice', $request->order_id)
                                  ->with('detailTransaksi.produk', 'pembayaranQris')
                                  ->lockForUpdate()
                                  ->first();

            if (!$transaksi) {
                return response()->json(['status' => 'transaksi tidak ditemukan'], 404);
            }

            $transaksi->terapkanStatusMidtrans($request->transaction_status, $request->all());

            return response()->json(['status' => 'ok']);
        });
    }

    // Dipanggil berulang (polling) dari halaman kasir untuk mengecek status pembayaran QRIS terkini, sebagai jalur cadangan kalau callback Midtrans telat masuk
    public function checkStatus($invoiceNumber)
    {
        $transaksi = Transaksi::where('nomor_invoice', $invoiceNumber)
                              ->where('pengguna_id', session('user_id'))
                              ->with('detailTransaksi.produk', 'pembayaranQris')
                              ->first();

        if (!$transaksi) {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        try {
            $statusMidtrans = $this->midtrans->cekStatus($invoiceNumber);
            $transaksi->terapkanStatusMidtrans($statusMidtrans->transaction_status);

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

    // Menampilkan & mencatat waktu cetak struk transaksi milik kasir yang sedang login (updateOrCreate agar tidak duplikat saat dicetak ulang)
    public function cetakStruk($invoice)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'pengguna'])
            ->where('nomor_invoice', $invoice)
            ->where('pengguna_id', session('user_id'))
            ->firstOrFail();

        CetakStruk::updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            ['waktu_cetak' => now()]
        );

        return view('kasir.struk', compact('transaksi'));
    }
}