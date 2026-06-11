<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_qris', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaksi_id')
                  ->constrained('transaksi')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete()
                  ->unique();

            $table->string('invoice_qris')->unique();
            $table->text('qris_string');
            $table->text('qris_gambar')->nullable();

            $table->decimal('nominal', 14, 2);

            $table->enum('status', [
                'menunggu',
                'berhasil',
                'gagal',
                'kedaluwarsa'
            ]);

            $table->dateTime('waktu_callback')->nullable();
            $table->json('data_callback')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_qris');
    }
};
