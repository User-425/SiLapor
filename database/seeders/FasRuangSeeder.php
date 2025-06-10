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
                'id_fasilitas' => 3, 
                'id_ruang' => 2,     
                'kode_fasilitas' => 'PT-RT03-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            
            [
                'id_fasilitas' => 1, 
                'id_ruang' => 3,     
                'kode_fasilitas' => 'PRJ-RT04-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 2, 
                'id_ruang' => 3,     
                'kode_fasilitas' => 'AC-RT04-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 3, 
                'id_ruang' => 3,     
                'kode_fasilitas' => 'PT-RT04-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 2, 
                'id_ruang' => 4,     
                'kode_fasilitas' => 'AC-RT05-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 1, 
                'id_ruang' => 4,     
                'kode_fasilitas' => 'PRJ-RT05-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 1, 
                'id_ruang' => 5,     
                'kode_fasilitas' => 'PRJ-RT06-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 2, 
                'id_ruang' => 5,     
                'kode_fasilitas' => 'AC-RT06-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 3, 
                'id_ruang' => 5,     
                'kode_fasilitas' => 'PT-RT06-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_fasilitas' => 3, 
                'id_ruang' => 6,     
                'kode_fasilitas' => 'PT-RT07-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('fasilitas_ruang')->insert($fasilitasRuang);
    }
}
