<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SiLapor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4>Admin Dashboard</h4>
            </div>
            <div class="card-body">
                <h5 class="card-title">Selamat Datang Admin, {{ Auth::user()->nama_lengkap }}</h5>
                <p class="card-text">Anda memiliki akses ke semua fungsi administrasi.</p>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Kelola Pengguna</h5>
                                <p class="card-text">Tambah, edit, dan hapus pengguna sistem</p>
                                <a href="#" class="btn btn-primary">Kelola</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Laporan Sistem</h5>
                                <p class="card-text">Lihat dan unduh semua laporan</p>
                                <a href="#" class="btn btn-primary">Lihat</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pengaturan</h5>
                                <p class="card-text">Konfigurasi sistem SiLapor</p>
                                <a href="#" class="btn btn-primary">Ubah</a>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
