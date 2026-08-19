<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    // minta snap token buat mulai pembayaran QRIS
    public function buatSnapToken(string $orderId, int $grossAmount): string
    {
        return Snap::getSnapToken([
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
        ]);
    }

    // cek keaslian callback dari Midtrans, biar gak ada yang bisa malsuin notifikasi
    public function verifikasiSignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $hashed = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));

        return hash_equals($hashed, $signatureKey);
    }

    // tanya langsung ke Midtrans, status transaksi ini sekarang gimana
    public function cekStatus(string $orderId): object
    {
        return Transaction::status($orderId);
    }
}