<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UmpanBalikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $umpanBalik = [
            [
                'id_laporan' => 3, // AC bocor yang sudah diperbaiki
                'id_pengguna' => 3, // User yang memberi feedback
                'rating' => 4,
                'komentar' => 'Perbaikan cepat dan AC sekarang sudah tidak bocor lagi. Sedikit masalah pada instalasi awal tapi sudah diperbaiki.',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'id_laporan' => 4, // Lampu proyektor redup
                'id_pengguna' => 2,
                'rating' => 5,
                'komentar' => 'Penggantian lampu proyektor sangat cepat dan kualitas gambar sekarang jauh lebih baik. Terima kasih!',
                'created_at' => Carbon::now()->subDays(17),
                'updated_at' => Carbon::now()->subDays(17),
            ],
        ];

        DB::table('umpan_balik')->insert($umpanBalik);
    }
}