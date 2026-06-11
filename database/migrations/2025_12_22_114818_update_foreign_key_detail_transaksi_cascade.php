<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        // Cara 1: Drop dan buat ulang constraint
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
    }

    public function down(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->foreign('produk_id')
                  ->references('id')
                  ->on('produk')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }
};