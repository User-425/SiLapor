<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KriteriaLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriteriaLaporan = [
            [
                'id_laporan' => 1,
                // Proyektor tidak menyala (status: menunggu_verifikasi)
                'tingkat_kerusakan_pelapor' => 4,
                'dampak_akademik_pelapor' => 5,
                'kebutuhan_pelapor' => 3,
                'tingkat_kerusakan_sarpras' => null,
                'dampak_akademik_sarpras' => null,
                'kebutuhan_sarpras' => null,
                'skor_prioritas' => null,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id_laporan' => 2,
                // AC berisik dan tidak dingin (status: diproses)
                'tingkat_kerusakan_pelapor' => 3,
                'dampak_akademik_pelapor' => 4,
                'kebutuhan_pelapor' => 5,
                'tingkat_kerusakan_sarpras' => 4,
                'dampak_akademik_sarpras' => 3,
                'kebutuhan_sarpras' => 4,
                'skor_prioritas' => 8,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'id_laporan' => 3,
                // AC bocor (status: diperbaiki)
                'tingkat_kerusakan_pelapor' => 5,
                'dampak_akademik_pelapor' => 5,
                'kebutuhan_pelapor' => 4,
                'tingkat_kerusakan_sarpras' => 5,
                'dampak_akademik_sarpras' => 5,
                'kebutuhan_sarpras' => 5,
                'skor_prioritas' => 10,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id_laporan' => 4,
                // Lampu proyektor redup (status: selesai)
                'tingkat_kerusakan_pelapor' => 3,
                'dampak_akademik_pelapor' => 4,
                'kebutuhan_pelapor' => 3,
                'tingkat_kerusakan_sarpras' => 2,
                'dampak_akademik_sarpras' => 3,
                'kebutuhan_sarpras' => 3,
                'skor_prioritas' => 6,
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'id_laporan' => 5,
                // Papan tulis sulit dibersihkan (status: ditolak)
                'tingkat_kerusakan_pelapor' => 2,
                'dampak_akademik_pelapor' => 3,
                'kebutuhan_pelapor' => 2,
                'tingkat_kerusakan_sarpras' => 1,
                'dampak_akademik_sarpras' => 2,
                'kebutuhan_sarpras' => 1,
                'skor_prioritas' => 2,
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'id_laporan' => 8,
                'tingkat_kerusakan_pelapor' => 5,
                'dampak_akademik_pelapor' => 5,
                'kebutuhan_pelapor' => 5,
                'tingkat_kerusakan_sarpras' => 5,
                'dampak_akademik_sarpras' => 5,
                'kebutuhan_sarpras' => 5,
                'skor_prioritas' => 10,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id_laporan' => 9,
                'tingkat_kerusakan_pelapor' => 4,
                'dampak_akademik_pelapor' => 4,
                'kebutuhan_pelapor' => 4,
                'tingkat_kerusakan_sarpras' => 4,
                'dampak_akademik_sarpras' => 3,
                'kebutuhan_sarpras' => 4,
                'skor_prioritas' => 8,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'id_laporan' => 11,
                'tingkat_kerusakan_pelapor' => 3,
                'dampak_akademik_pelapor' => 4,
                'kebutuhan_pelapor' => 4,
                'tingkat_kerusakan_sarpras' => 3,
                'dampak_akademik_sarpras' => 4,
                'kebutuhan_sarpras' => 3,
                'skor_prioritas' => 7,
                'created_at' => Carbon::now()->subDays(13),
                'updated_at' => Carbon::now()->subDays(11),
            ],
            [
                'id_laporan' => 12,
                'tingkat_kerusakan_pelapor' => 3,
                'dampak_akademik_pelapor' => 4,
                'kebutuhan_pelapor' => 3,
                'tingkat_kerusakan_sarpras' => 2,
                'dampak_akademik_sarpras' => 3,
                'kebutuhan_sarpras' => 3,
                'skor_prioritas' => 6,
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'id_laporan' => 16,
                'tingkat_kerusakan_pelapor' => 3,
                'dampak_akademik_pelapor' => 3,
                'kebutuhan_pelapor' => 4,
                'tingkat_kerusakan_sarpras' => 3,
                'dampak_akademik_sarpras' => 2,
                'kebutuhan_sarpras' => 3,
                'skor_prioritas' => 5,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'id_laporan' => 17,
                'tingkat_kerusakan_pelapor' => 5,
                'dampak_akademik_pelapor' => 5,
                'kebutuhan_pelapor' => 5,
                'tingkat_kerusakan_sarpras' => 4,
                'dampak_akademik_sarpras' => 5,
                'kebutuhan_sarpras' => 4,
                'skor_prioritas' => 9,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id_laporan' => 18,
                'tingkat_kerusakan_pelapor' => 4,
                'dampak_akademik_pelapor' => 4,
                'kebutuhan_pelapor' => 3,
                'tingkat_kerusakan_sarpras' => 3,
                'dampak_akademik_sarpras' => 4,
                'kebutuhan_sarpras' => 4,
                'skor_prioritas' => 7,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(3),
            ],
        ];

        DB::table('kriteria_laporan')->insert($kriteriaLaporan);
    }
}
