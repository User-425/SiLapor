<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengguna::create([
            'nama_pengguna' => 'Admin',
            'kata_sandi' => Hash::make('password'),
            'peran' => 'admin',
            'nama_lengkap' => 'Administrator Kece',
            'email' => 'admin@silapor.com',
            'nomor_telepon' => '081234567890',
        ]);

        Pengguna::create([
            'nama_pengguna' => 'Mahasiswa',
            'kata_sandi' => Hash::make('password'),
            'peran' => 'mahasiswa',
            'nama_lengkap' => 'Mahasiswa Pintar',
            'email' => 'mahasiswa@silapor.com',
            'nomor_telepon' => '081234567891',
        ]);

        Pengguna::create([
            'nama_pengguna' => 'SarPras',
            'kata_sandi' => Hash::make('password'),
            'peran' => 'sarpras',
            'nama_lengkap' => 'Sarpras Keren',
            'email' => 'sarpras@silapor.com',
            'nomor_telepon' => '081234567892',
        ]);

        Pengguna::create([
            'nama_pengguna' => 'Teknisi',
            'kata_sandi' => Hash::make('password'),
            'peran' => 'teknisi',
            'nama_lengkap' => 'Teknisi Cepat',
            'email' => 'teknisi@silapor.com',
            'nomor_telepon' => '081234567893',
        ]);

    }
}
