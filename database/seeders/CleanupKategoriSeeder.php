<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanupKategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID kategori yang akan dihapus
        $kategoriHapus = DB::table('kategori')
            ->whereIn('nama', ['Elektronik', 'Alat Tulis'])
            ->pluck('id');

        if ($kategoriHapus->isEmpty()) {
            $this->command->info('Kategori Elektronik dan Alat Tulis sudah tidak ada.');
            return;
        }

        // Hapus produk yang menggunakan kategori tersebut
        $produkDihapus = DB::table('produk')
            ->whereIn('kategori_id', $kategoriHapus)
            ->delete();

        $this->command->info("Menghapus {$produkDihapus} produk...");

        // Hapus kategori
        $kategoriDihapus = DB::table('kategori')
            ->whereIn('nama', ['Elektronik', 'Alat Tulis'])
            ->delete();

        $this->command->info("Menghapus {$kategoriDihapus} kategori...");
        $this->command->info('Cleanup selesai!');
    }
}