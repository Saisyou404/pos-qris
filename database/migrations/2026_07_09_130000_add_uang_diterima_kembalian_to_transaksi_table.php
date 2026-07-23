<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom uang_diterima & kembalian di tabel transaksi.
     * Sebelumnya nilai ini hanya dihitung di browser (JS) dan tidak pernah
     * disimpan, sehingga tidak ada jejak audit untuk rekonsiliasi kas tunai.
     * Nullable karena tidak relevan untuk transaksi QRIS.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->decimal('uang_diterima', 14, 2)->nullable()->after('total_pembayaran');
            $table->decimal('kembalian', 14, 2)->nullable()->after('uang_diterima');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['uang_diterima', 'kembalian']);
        });
    }
};