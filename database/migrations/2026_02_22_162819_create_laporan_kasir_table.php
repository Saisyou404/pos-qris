<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('laporan_kasir', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
        $table->date('tanggal');
        $table->integer('total_transaksi');
        $table->bigInteger('total_penjualan');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kasir');
    }
};
