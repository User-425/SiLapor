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
                'deskripsi_lokasi' => 'Lab Pemrograman 07, Lantai 7 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Additional rooms with only RT and LPR prefixes
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT03',
                'deskripsi_lokasi' => 'Ruang Teori 03, Lantai 5 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT04',
                'deskripsi_lokasi' => 'Ruang Teori 04, Lantai 5 Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT05',
                'deskripsi_lokasi' => 'Ruang Teori 05, Lantai 6 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT06',
                'deskripsi_lokasi' => 'Ruang Teori 06, Lantai 2 Utara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'LPR01',
                'deskripsi_lokasi' => 'Lab Pemrograman 01, Lantai 3 Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT07',
                'deskripsi_lokasi' => 'Ruang Teori 07, Lantai 4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT08',
                'deskripsi_lokasi' => 'Ruang Teori 08, Lantai 4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1, 
                'nama_ruang' => 'RT09',
                'deskripsi_lokasi' => 'Ruang Teori 09, Lantai 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'LPR02',
                'deskripsi_lokasi' => 'Lab Pemrograman 02, Lantai 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT10',
                'deskripsi_lokasi' => 'Ruang Teori 10, Lantai 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gedung' => 1,
                'nama_ruang' => 'RT11',
                'deskripsi_lokasi' => 'Ruang Teori 11, Lantai 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('ruang')->insert($ruang);
    }
}
