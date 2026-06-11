<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaksi_id')
                  ->constrained('transaksi')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('produk_id')
                  ->constrained('produk')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 14, 2);

            $table->timestamps();

            $table->unique(['transaksi_id', 'produk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
