<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah data sudah ada, jika sudah skip
        if (DB::table('pengguna')->count() > 0) {
            $this->command->info('Data pengguna sudah ada, skip seeding.');
            return;
        }

        DB::table('pengguna')->insert([
            [
                'nama' => 'Admin',
                'email' => 'admin@pos.test',
                'kata_sandi' => Hash::make('admin123'),
                'peran' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kasir',
                'email' => 'kasir@pos.test',
                'kata_sandi' => Hash::make('kasir123'),
                'peran' => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Data pengguna berhasil ditambahkan.');
    }
}