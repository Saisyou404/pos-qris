<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pengguna_id')
                  ->constrained('pengguna')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->string('nomor_invoice')->unique();
            $table->dateTime('tanggal_transaksi');
            $table->decimal('total_pembayaran', 14, 2);

            $table->enum('metode_pembayaran', ['tunai', 'qris']);
            $table->enum('status', ['pending', 'dibayar', 'dibatalkan']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
