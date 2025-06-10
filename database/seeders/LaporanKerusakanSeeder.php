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
            // MENUNGGU VERIFIKASI REPORTS - should not have a batch
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 1,
                'deskripsi' => 'Saat menggunakan proyektor LCD di ruang kelas, gambar yang ditampilkan terlihat buram dan tidak jelas. Kami sudah mencoba mengatur fokusnya, tapi tetap tidak terlalu membaik.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.3UPOY4kffVN6YkeNRAXnJgAAAA?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'menunggu_verifikasi',
                'ranking' => 3,
                'id_batch' => null, // No batch for unverified reports
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id_pengguna' => 3,
                'id_fas_ruang' => 1,
                'deskripsi' => 'Tampilan proyektor LCD di ruang kuliah 2A buram dan kurang tajam. Penyesuaian fokus tidak memberikan perbaikan signifikan.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.3UPOY4kffVN6YkeNRAXnJgAAAA?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'menunggu_verifikasi',
                'ranking' => 3,
                'id_batch' => null, // No batch for unverified reports
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 6,
                'deskripsi' => 'Keyboard komputer di Lab Komputer 1 beberapa tombol tidak berfungsi, terutama huruf A, S, D, dan F.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.IbGdIQG5_cj9yCL1S2_SBAHaE7?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'menunggu_verifikasi',
                'ranking' => 2,
                'id_batch' => null, // No batch for unverified reports
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            
            // DITOLAK REPORTS - should not have a batch
            [
                'id_pengguna' => 3,
                'id_fas_ruang' => 5,
                'deskripsi' => 'Papan tulis sulit dibersihkan dan ada bekas tulisan permanen. Perlu dibersihkan dengan cairan khusus.',
                'url_foto' => 'https://cdn.shopify.com/s/files/1/0841/7355/files/Old_f39f97d3-6e00-482a-a0ac-7e54c55dc3d6_480x480.jpg?v=1679622555',
                'status' => 'ditolak',
                'ranking' => 1,
                'id_batch' => null, // No batch for rejected reports
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 7,
                'deskripsi' => 'Ada kecoa mati di sudut ruangan, mohon dibersihkan.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.kUy6p_6yPeA9PEReqXDOnQHaFj?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'ditolak',
                'ranking' => 1,
                'id_batch' => null, // No batch for rejected reports
                'created_at' => Carbon::now()->subDays(16),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            
            // BATCH 1 - AC Repairs (aktif)
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 3,
                'deskripsi' => 'AC mengeluarkan bunyi keras dan tidak dingin. Remote AC juga tidak berfungsi dengan baik.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.nQgl0FsDHvTOm7XaXF1gbwAAAA?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'diproses',
                'ranking' => 4,
                'id_batch' => 1, 
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'id_pengguna' => 3,
                'id_fas_ruang' => 4,
                'deskripsi' => 'AC bocor dan mengeluarkan air cukup banyak. Ada genangan di lantai yang berbahaya.',
                'url_foto' => 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/window-ac-1525200738.jpg',
                'status' => 'diproses',
                'ranking' => 5,
                'id_batch' => 1, 
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 8,
                'deskripsi' => 'AC di ruang rapat 3 terlalu dingin dan tidak bisa diatur temperaturnya.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.K1tbvzQoXN7CnZyiT9CIlwHaHa?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'diperbaiki',
                'ranking' => 3,
                'id_batch' => 1,
                'created_at' => Carbon::now()->subDays(9),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id_pengguna' => 3,
                'id_fas_ruang' => 9,
                'deskripsi' => 'AC di ruang dosen mengeluarkan bau tidak sedap ketika dinyalakan.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.wyTlVROyIBRrrHf-EsYiYAHaE7?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'diperbaiki',
                'ranking' => 4,
                'id_batch' => 1,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            
            // BATCH 2 - Projector Repairs (draft)
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 2,
                'deskripsi' => 'Lampu proyektor meredup dan gambar tidak jelas. Perlu penggantian lampu.',
                'url_foto' => 'https://powerfactoryproductions.com/wp-content/uploads/2016/12/VGA_projector_03252014.jpg',
                'status' => 'selesai',
                'ranking' => 2,
                'id_batch' => 2, 
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'id_pengguna' => 3,
                'id_fas_ruang' => 10,
                'deskripsi' => 'Proyektor di ruang seminar tidak terkoneksi dengan komputer, sering terputus.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.Xhhm9hid4m2_1e_xFGXiCAHaE8?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'diproses',
                'ranking' => 3,
                'id_batch' => 2,
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 11,
                'deskripsi' => 'Proyektor di ruang kelas 4B berkedip-kedip dan terkadang mati sendiri.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.7gcCgkHOYLX7NObRCBrw0QHaFj?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'diproses',
                'ranking' => 4,
                'id_batch' => 2,
                'created_at' => Carbon::now()->subDays(13),
                'updated_at' => Carbon::now()->subDays(11),
            ],
            
            // BATCH 3 - Whiteboard Replacement (selesai)
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 12,
                'deskripsi' => 'Papan tulis di ruang kelas 1A sudah kusam dan sulit untuk menghapus tulisan.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.3bXR08ZQd_a6nohwSmxfBgHaFj?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'selesai',
                'ranking' => 3,
                'id_batch' => 3,
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'id_pengguna' => 3,
                'id_fas_ruang' => 13,
                'deskripsi' => 'Papan tulis retak di bagian tengah, perlu diganti karena berbahaya.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.y_oEUKhWLFLiYgxpmD6pNAHaFI?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'selesai',
                'ranking' => 5,
                'id_batch' => 3,
                'created_at' => Carbon::now()->subDays(29),
                'updated_at' => Carbon::now()->subDays(21),
            ],
            [
                'id_pengguna' => 2,
                'id_fas_ruang' => 14,
                'deskripsi' => 'Papan tulis di ruang tutorial terlalu kecil dan tidak cukup untuk menulis materi.',
                'url_foto' => 'https://th.bing.com/th/id/OIP.4K79aSL0wnj1GgkynlIfIAHaFc?cb=iwc2&rs=1&pid=ImgDetMain',
                'status' => 'selesai',
                'ranking' => 2,
                'id_batch' => 3,
                'created_at' => Carbon::now()->subDays(28),
                'updated_at' => Carbon::now()->subDays(22),
            ],
        ];

        DB::table('laporan_kerusakan')->insert($laporanKerusakan);
    }
}
