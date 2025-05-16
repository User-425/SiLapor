<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GedungSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gedung')->insert([
            'nama_gedung' => 'Gedung Sipil',
            'deskripsi_lokasi' => 'Gedung utama jurusan teknik sipil, lantai 3.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
