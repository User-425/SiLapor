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
                'nama_periode' => 'Periode Q1 2025',
                'tanggal_mulai' => '2025-01-01',
                'tanggal_selesai' => '2025-03-31',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_periode' => 'Periode Q2 2025',
                'tanggal_mulai' => '2025-04-01',
                'tanggal_selesai' => '2025-06-30',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_periode' => 'Periode Q3 2025',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2025-09-30',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_periode' => 'Periode Q4 2025',
                'tanggal_mulai' => '2025-10-01',
                'tanggal_selesai' => '2025-12-31',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('periode')->insert($periodes);
    }
}
