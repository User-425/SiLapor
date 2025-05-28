<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PenggunaFactory extends Factory
{
    protected $model = \App\Models\Pengguna::class;

    public function definition()
    {
        return [
            'nama_pengguna' => $this->faker->unique()->userName,
            'kata_sandi' => Hash::make('password'),
            'peran' => $this->faker->randomElement(['admin', 'mahasiswa', 'dosen', 'tendik', 'sarpras', 'teknisi']),
            'nama_lengkap' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'nomor_telepon' => $this->faker->phoneNumber,
        ];
    }
}