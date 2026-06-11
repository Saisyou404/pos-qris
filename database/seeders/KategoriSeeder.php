<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Snack', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Elektronik', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Alat Tulis', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('kategori')->insert($kategori);
    }
}