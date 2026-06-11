<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cetak_struk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaksi_id')
                  ->constrained('transaksi')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->dateTime('waktu_cetak');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cetak_struk');
    }
};
