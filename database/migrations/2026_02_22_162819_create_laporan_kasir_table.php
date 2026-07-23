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

            $table->foreignId('pengguna_id')
                  ->constrained('pengguna')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // jangan hapus akun kasir jika masih punya laporan historis

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->integer('total_transaksi')->default(0);
            $table->decimal('total_pendapatan', 14, 2)->default(0);
            $table->decimal('pendapatan_tunai', 14, 2)->default(0);
            $table->decimal('pendapatan_qris', 14, 2)->default(0);

            $table->enum('kondisi_toko', ['baik', 'sedang', 'buruk'])->default('baik');
            $table->text('catatan_kejadian')->nullable();
            $table->text('saran')->nullable();

            $table->timestamps();

            // Satu kasir hanya bisa submit 1 laporan per hari
            $table->unique(['pengguna_id', 'tanggal']);
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