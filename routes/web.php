<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\FasRuangController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\TeknisiController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [AuthController::class, 'showLoginForm']);
Route::post('/', action: [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{role}', [DashboardController::class, 'showByRole'])->name('dashboard.role');

    Route::get('/profile', [PenggunaController::class, 'profile'])->name('profile');
    Route::post('/profile', [PenggunaController::class, 'updateProfile'])->name('profile.update');

    // Middleware for admin
    Route::middleware(['peran:admin'])->group(function () {
        Route::resource('pengguna', PenggunaController::class)->names('users')->except(['show']);
        Route::resource('fasilitas', FasRuangController::class)->names('fasilitas')->except(['show']);
        Route::resource('tipe_fasilitas', FasilitasController::class)->names('tipe_fasilitas')->except(['show']);
        Route::resource('ruang', RuangController::class)->except(['show']);
        Route::resource('gedung', GedungController::class)->except(['show']);
        Route::resource('periode', PeriodeController::class);
    });

    // Laporan
    Route::resource('laporan', LaporanKerusakanController::class);
    Route::get('/laporan/detail/{laporan}', [LaporanKerusakanController::class, 'getDetail'])->name('laporan.detail');
    Route::get('/laporan/fasilitas-by-ruang/{ruang_id}', [LaporanKerusakanController::class, 'getFasilitasByRuang']);
    Route::get('/laporan/kode-by-ruang-fasilitas/{ruang_id}/{fasilitas_id}', [LaporanKerusakanController::class, 'getKodeByRuangFasilitas']);
     Route::get('/fasilitas/{id}/qr', [FasRuangController::class, 'generateQR'])
        ->name('fasilitas.qr')
        ->middleware('peran:admin,sarpras');
        
    Route::get('/laporan/quick/{code}', [LaporanKerusakanController::class, 'quickReport'])
        ->name('laporan.quick');

    // Routes untuk Sarpras 
    Route::middleware(['peran:sarpras'])->group(function () {
        Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/create/{id_laporan}', [TugasController::class, 'create'])->name('tugas.create');
        Route::post('/tugas', [TugasController::class, 'store'])->name('tugas.store');
        Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('tugas.show');
        Route::get('/tugas/{id}/edit', [TugasController::class, 'edit'])->name('tugas.edit');
        Route::put('/tugas/{id}', [TugasController::class, 'update'])->name('tugas.update');
        Route::delete('/tugas/{id}', [TugasController::class, 'destroy'])->name('tugas.destroy');
    });
    // Tambahan rute untuk sarpras dan teknisi
    Route::middleware(['peran:sarpras'])->group(function () {
        Route::post('/laporan/{laporan}/verifikasi', [LaporanKerusakanController::class, 'verifikasi'])->name('laporan.verifikasi');
        Route::post('/laporan/batch-update-status', [LaporanKerusakanController::class, 'batch-updateStatus'])->name('laporan.batchUpdateStatus');
        Route::get('/laporan/export', [LaporanKerusakanController::class, 'export'])->name('laporan.export');
    });
    Route::middleware(['peran:sarpras,teknisi'])->group(function () {
        Route::post('/laporan/{laporan}/status', [LaporanKerusakanController::class, 'updateStatus'])->name('laporan.updateStatus');
    });

    // Routes untuk teknisi
    Route::middleware(['auth', 'peran:teknisi'])->group(function () {
    Route::get('/teknisi/tugas', [TeknisiController::class, 'index'])->name('teknisi.index');
    Route::put('/teknisi/tugas/{id}', [TeknisiController::class, 'updateTugas'])->name('teknisi.updateTugas');
    Route::get('/teknisi/riwayat', [TeknisiController::class, 'riwayatPerbaikan'])->name('teknisi.riwayat');
});
});