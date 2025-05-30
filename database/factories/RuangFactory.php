<?php
namespace Database\Factories;

use App\Models\Gedung;
use Illuminate\Database\Eloquent\Factories\Factory;

class RuangFactory extends Factory
{
    protected $model = \App\Models\Ruang::class;

    public function definition()
    {
        return [
            'id_gedung' => Gedung::factory(),
            'nama_ruang' => 'Ruang ' . $this->faker->numberBetween(101, 999),
            'deskripsi_lokasi' => $this->faker->sentence,
        ];
    }
}