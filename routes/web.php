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
        Route::resource('fasilitas', FasRuangController::class)->except(['show']);
        Route::resource('tipe_fasilitas', FasilitasController::class)->except(['show']);
        Route::resource('ruang', RuangController::class)->except(['show']);
        Route::resource('gedung', GedungController::class)->except(['show']);
        Route::resource('periode', PeriodeController::class);
    });

    // Laporan
    Route::resource('laporan', LaporanKerusakanController::class);
    Route::get('/laporan/detail/{laporan}', [LaporanKerusakanController::class, 'getDetail'])->name('laporan.detail');
    Route::get('/laporan_ids/fasilitas-laporan_by_ruang/{ruang_id}', [LaporanKerusakanController::class, 'getFasilitasilitasByRuang']);
    Route::get('/laporan/kode-by-ruang-fasilitas/{ruang_id}/{fasilitas_id}', [LaporanKerusakanController::class, 'getKodeByRuangFasilitas']);
    // Tambahan rute untuk sarpras dan teknisi
    Route::middleware(['peran:sarpras'])->group(function () {
        Route::post('/laporan/{laporan}/verifikasi', [LaporanKerusakanController::class, 'verifikasi'])->name('laporan.verifikasi');
        Route::post('/laporan/batch-update-status', [LaporanKerusakanController::class, 'batch-updateStatus'])->name('laporan.batchUpdateStatus');
        Route::get('/laporan/export', [LaporanKerusakanController::class, 'export'])->name('laporan.export');
    });
    Route::middleware(['peran:sarpras,teknisi'])->group(function () {
        Route::post('/laporan/{laporan}/status', [LaporanKerusakanController::class, 'updateStatus'])->name('laporan.updateStatus');
    });
});