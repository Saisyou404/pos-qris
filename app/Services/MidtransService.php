<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

/**
 * Membungkus seluruh interaksi dengan payment gateway Midtrans.
 *
 * Sengaja dibuat sebagai Service terpisah (bukan method di Model), karena
 * Model seharusnya hanya bertanggung jawab atas data & aturan bisnis milik
 * aplikasi sendiri — bukan komunikasi ke sistem/API pihak ketiga.
 * Controller cukup memanggil service ini tanpa perlu tahu detail Midtrans.
 */
class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Minta Snap Token dari Midtrans untuk memulai pembayaran QRIS.
     */
    public function buatSnapToken(string $orderId, int $grossAmount): string
    {
        return Snap::getSnapToken([
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
        ]);
    }

    /**
     * Verifikasi keaslian signature yang dikirim Midtrans lewat callback,
     * supaya callback palsu (bukan dari Midtrans) tidak bisa mengubah status transaksi.
     */
    public function verifikasiSignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $hashed = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));

        return hash_equals($hashed, $signatureKey);
    }

    /**
     * Cek status transaksi terbaru langsung ke Midtrans (dipakai untuk polling).
     */
    public function cekStatus(string $orderId): object
    {
        return Transaction::status($orderId);
    }
}
