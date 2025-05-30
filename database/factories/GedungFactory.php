<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GedungFactory extends Factory
{
    protected $model = \App\Models\Gedung::class;

    public function definition()
    {
        return [
            'nama_gedung' => 'Gedung ' . $this->faker->randomLetter,
            'deskripsi_lokasi' => $this->faker->sentence,
        ];
    }
}