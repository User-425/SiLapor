<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tugas = [
            [
                'id_laporan' => 2, // AC berisik dan tidak dingin
                'id_pengguna' => 5, // Teknisi user
                'catatan' => 'AC perlu dibersihkan filter dan pengecekan freon',
                'status' => 'dikerjakan',
                'tanggal_selesai' => null,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id_laporan' => 3, // AC bocor
                'id_pengguna' => 5,
                'catatan' => 'Kebocoran pada pipa pembuangan, perlu penggantian selang',
                'status' => 'selesai',
                'tanggal_selesai' => Carbon::now()->subDays(12),
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'id_laporan' => 4, // Lampu proyektor redup
                'id_pengguna' => 5,
                'catatan' => 'Lampu sudah diganti dengan yang baru',
                'status' => 'selesai',
                'tanggal_selesai' => Carbon::now()->subDays(18),
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(18),
            ],
        ];

        DB::table('tugas')->insert($tugas);
    }
}