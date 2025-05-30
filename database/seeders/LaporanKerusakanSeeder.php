<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LaporanKerusakan;

class LaporanKerusakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laporanKerusakan = [
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 1,
                'deskripsi' => 'Proyektor tidak menyala ketika disambungkan ke laptop. Sudah coba ganti kabel HDMI tapi masih tidak berfungsi.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.3UPOY4kffVN6YkeNRAXnJgAAAA?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'menunggu_verifikasi',
                'ranking' => 3,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 3,
                'deskripsi' => 'AC mengeluarkan bunyi keras dan tidak dingin. Remote AC juga tidak berfungsi dengan baik.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.nQgl0FsDHvTOm7XaXF1gbwAAAA?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'diproses',
                'ranking' => 4,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'id_pengguna' => 3,
                'id_fas_ruang' => 4,
                'deskripsi' => 'AC bocor dan mengeluarkan air cukup banyak. Ada genangan di lantai yang berbahaya.',
                'url_foto' => 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/window-ac-1525200738.jpg',
                'status' => 'diperbaiki',
                'ranking' => 5,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 2,
                'deskripsi' => 'Lampu proyektor meredup dan gambar tidak jelas. Perlu penggantian lampu.',
                'url_foto' => 'https://powerfactoryproductions.com/wp-content/uploads/2016/12/VGA_projector_03252014.jpg',
                'status' => 'selesai',
                'ranking' => 2,
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'id_pengguna' => 3,
                'id_fas_ruang' => 5,
                'deskripsi' => 'Papan tulis sulit dibersihkan dan ada bekas tulisan permanen. Perlu dibersihkan dengan cairan khusus.',
                'url_foto' => 'https://cdn.shopify.com/s/files/1/0841/7355/files/Old_f39f97d3-6e00-482a-a0ac-7e54c55dc3d6_480x480.jpg?v=1679622555',
                'status' => 'ditolak',
                'ranking' => 1,
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(10),
            ],
        ];

        DB::table('laporan_kerusakan')->insert($laporanKerusakan);

        // LaporanKerusakan::factory()->count(10)->create();
        // LaporanKerusakan::factory()->count(5)->prioritasTinggi()->menungguVerifikasi()->create();
        // LaporanKerusakan::factory()->count(3)->diproses()->create();
    }
}
