<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FasilitasFactory extends Factory
{
    protected $model = \App\Models\Fasilitas::class;

    public function definition()
    {
        return [
            'nama_fasilitas' => $this->faker->randomElement(['AC', 'Proyektor', 'Meja', 'Kursi', 'Papan Tulis']),
            'deskripsi' => $this->faker->sentence,
        ];
    }
}