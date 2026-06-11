<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kategori_id')
                  ->constrained('kategori')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->string('nama');
            $table->decimal('harga', 12, 2);
            $table->integer('stok');
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
