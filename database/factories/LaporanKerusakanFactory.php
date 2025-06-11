<?php

namespace Database\Factories;

use App\Models\LaporanKerusakan;
use App\Models\FasRuang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LaporanKerusakan>
 */
class LaporanKerusakanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LaporanKerusakan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['menunggu_verifikasi', 'diproses', 'diperbaiki', 'selesai', 'ditolak'];
        $createdAt = $this->faker->dateTimeBetween('-400 days', 'now');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        
        // First determine the facility room
        $fasRuangId = $this->faker->numberBetween(1, 5);
        
        // Get the image URL based on the facility type
        $url = $this->getFasilitasImageUrl($fasRuangId);

        return [
            'id_pengguna' => $this->faker->numberBetween(2, 3),
            'id_fas_ruang' => $fasRuangId,
            'deskripsi' => $this->faker->paragraph(3),
            'url_foto' => $url,
            'status' => $this->faker->randomElement($statuses),
            'ranking' => $this->faker->numberBetween(1, 5),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    /**
     * Get appropriate image URL based on the fasilitas type
     */
    protected function getFasilitasImageUrl(int $fasRuangId): string
    {
        // Mapping based on the FasRuangSeeder data
        $facilityTypeMap = [
            1 => 'Proyektor', // PRJ-RT01-01
            2 => 'Proyektor', // PRJ-RT01-02
            3 => 'AC',        // AC-RT01-01
            4 => 'AC',        // AC-RT02-01
            5 => 'Papan Tulis' // PT-RT03-01
        ];

        $facilityType = $facilityTypeMap[$fasRuangId] ?? 'Fasilitas';
        
        return "https://via.placeholder.com/800x600.png?text=Kerusakan+" . str_replace(' ', '+', $facilityType);
    }

    /**
     * Indicate that the report is pending verification.
     */
    public function menungguVerifikasi(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'menunggu_verifikasi',
        ]);
    }

    /**
     * Indicate that the report is being processed.
     */
    public function diproses(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diproses',
        ]);
    }

    /**
     * Indicate that the report is being repaired.
     */
    public function diperbaiki(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diperbaiki',
        ]);
    }

    /**
     * Indicate that the report is completed.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
        ]);
    }

    /**
     * Indicate that the report is rejected.
     */
    public function ditolak(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ditolak',
        ]);
    }

    /**
     * Set a high priority report.
     */
    public function prioritasTinggi(): static
    {
        return $this->state(fn (array $attributes) => [
            'ranking' => 5,
        ]);
    }
}