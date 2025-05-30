<?php
namespace Database\Factories;

use App\Models\Fasilitas;
use App\Models\Ruang;
use Illuminate\Database\Eloquent\Factories\Factory;

class FasRuangFactory extends Factory
{
    protected $model = \App\Models\FasilitasRuang::class;

    public function definition()
    {
        return [
            'id_fasilitas' => Fasilitas::factory(),
            'id_ruang' => Ruang::factory(),
            'kode_fasilitas' => 'FAS-' . $this->faker->unique()->numberBetween(1000, 9999),
        ];
    }
}