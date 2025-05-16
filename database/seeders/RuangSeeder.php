<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $ruang = [
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT01',
                'deskripsi_lokasi' => 'Ruang Teori 01, Lantai 5 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT02',
                'deskripsi_lokasi' => 'Ruang Teori 02, Lantai 5 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'LPR07',
                'deskripsi_lokasi' => 'Lab Pemrograman, Lantai 7 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('ruang')->insert($ruang);
    }
}
