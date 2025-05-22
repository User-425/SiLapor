<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $fasilitas = [
            [
                'nama_fasilitas' => 'Proyektor',
                'deskripsi' => 'Proyektor Epson',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_fasilitas' => 'AC',
                'deskripsi' => 'AC LG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_fasilitas' => 'Papan Tulis',
                'deskripsi' => 'Papan tulis putih',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('fasilitas')->insert($fasilitas);
    }
}
