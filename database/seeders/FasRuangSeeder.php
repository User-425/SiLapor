<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasRuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fasilitasRuang = [
            [
                'id_fasilitas' => 1, 
                'id_ruang' => 1, 
                'kode_fasilitas' => 'PRJ-RT01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 1, 
                'id_ruang' => 1, 
                'kode_fasilitas' => 'PRJ-RT01-02',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 2,
                'id_ruang' => 1, 
                'kode_fasilitas' => 'AC-RT01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 2,
                'id_ruang' => 2, 
                'kode_fasilitas' => 'AC-RT02-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 2,
                'id_ruang' => 2,
                'kode_fasilitas' => 'PT-RT03-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('fasilitas_ruang')->insert($fasilitasRuang);
    }
}