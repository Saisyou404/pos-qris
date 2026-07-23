<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 1) Tambah kolom `deleted_at` di `produk` supaya bisa soft-delete
     *    (produk "dihapus" cukup disembunyikan, tidak benar-benar hilang).
     * 2) Kembalikan FK `detail_transaksi.produk_id` dari CASCADE ke RESTRICT,
     *    supaya riwayat transaksi lama tidak ikut terhapus saat produk
     *    di-nonaktifkan/dihapus.
     */
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->softDeletes(); // kolom nullable `deleted_at`
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeign('detail_transaksi_produk_id_foreign');
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->foreign('produk_id')
                  ->references('id')
                  ->on('produk')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeign('detail_transaksi_produk_id_foreign');
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->foreign('produk_id')
                  ->references('id')
                  ->on('produk')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};