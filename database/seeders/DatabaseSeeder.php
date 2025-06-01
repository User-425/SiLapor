<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PenggunaSeeder::class,
            GedungSeeder::class,
            PeriodeSeeder::class,
            RuangSeeder::class,
            FasilitasSeeder::class,
            FasRuangSeeder::class,
            LaporanKerusakanSeeder::class,
            KriteriaLaporanSeeder::class,
        ]);
    }
}
