<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $produk = [
            // Makanan (kategori_id: 1)
            [
                'kategori_id' => 1,
                'nama' => 'Nasi Goreng',
                'harga' => 15000,
                'stok' => 50,
                'deskripsi' => 'Nasi goreng spesial dengan telur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 1,
                'nama' => 'Mie Ayam',
                'harga' => 12000,
                'stok' => 40,
                'deskripsi' => 'Mie ayam dengan pangsit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 1,
                'nama' => 'Ayam Geprek',
                'harga' => 18000,
                'stok' => 30,
                'deskripsi' => 'Ayam crispy dengan sambal geprek',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Minuman (kategori_id: 2)
            [
                'kategori_id' => 2,
                'nama' => 'Es Teh Manis',
                'harga' => 5000,
                'stok' => 100,
                'deskripsi' => 'Es teh manis segar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'nama' => 'Es Jeruk',
                'harga' => 7000,
                'stok' => 80,
                'deskripsi' => 'Es jeruk peras segar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'nama' => 'Kopi Susu',
                'harga' => 10000,
                'stok' => 60,
                'deskripsi' => 'Kopi susu hangat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'nama' => 'Cappuccino',
                'harga' => 15000,
                'stok' => 50,
                'deskripsi' => 'Cappuccino premium',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Snack (kategori_id: 3)
            [
                'kategori_id' => 3,
                'nama' => 'Keripik Kentang',
                'harga' => 8000,
                'stok' => 70,
                'deskripsi' => 'Keripik kentang renyah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'nama' => 'Biskuit Cokelat',
                'harga' => 6000,
                'stok' => 90,
                'deskripsi' => 'Biskuit rasa cokelat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'nama' => 'Kacang Garuda',
                'harga' => 5000,
                'stok' => 100,
                'deskripsi' => 'Kacang goreng',
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];

        DB::table('produk')->insert($produk);
    }
}