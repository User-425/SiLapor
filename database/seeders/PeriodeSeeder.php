<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodes = [
            [
                'nama_periode' => 'Semester 1 2025',
                'tanggal_mulai' => '2025-01-01',
                'tanggal_selesai' => '2025-06-30',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_periode' => 'Semester 2 2025',
                'tanggal_mulai' => '2025-06-01',
                'tanggal_selesai' => '2025-12-30',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_periode' => 'Semester 1 2026',
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2026-06-30',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_periode' => 'Semester 2 2026',
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2026-12-31',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('periode')->insert($periodes);
    }
}
