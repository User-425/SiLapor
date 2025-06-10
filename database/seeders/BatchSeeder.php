<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batches = [
            [
                'nama_batch' => 'Batch Perbaikan AC Juni 2025',
                'status' => 'aktif',
                'tanggal_mulai' => '2025-06-10',
                'tanggal_selesai' => '2025-06-20',
                'catatan' => 'Prioritaskan perbaikan untuk ruangan yang sering digunakan untuk perkuliahan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_batch' => 'Batch Perbaikan Proyektor Juli 2025',
                'status' => 'draft',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2025-07-15',
                'catatan' => 'Perlu koordinasi dengan pengadaan untuk penggantian lampu proyektor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_batch' => 'Batch Penggantian Papan Tulis',
                'status' => 'selesai',
                'tanggal_mulai' => '2025-05-15',
                'tanggal_selesai' => '2025-05-25',
                'catatan' => 'Sudah selesai dilaksanakan dengan bantuan vendor eksternal',
                'created_at' => Carbon::now()->subMonth(),
                'updated_at' => Carbon::now()->subDays(15),
            ],
        ];

        DB::table('batches')->insert($batches);
    }
}