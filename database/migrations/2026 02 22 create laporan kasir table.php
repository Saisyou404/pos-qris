<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kasir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('total_transaksi')->default(0);
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('pendapatan_tunai',  15, 2)->default(0);
            $table->decimal('pendapatan_qris',   15, 2)->default(0);
            $table->enum('kondisi_toko', ['baik', 'sedang', 'buruk'])->default('baik');
            $table->text('catatan_kejadian')->nullable();
            $table->text('saran')->nullable();
            $table->timestamps();

            // Satu kasir hanya bisa submit 1 laporan per hari
            $table->unique(['pengguna_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kasir');
    }
};