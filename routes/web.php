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
use App\Http\Controllers\SarprasLaporanController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\NotificationController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{role}', [DashboardController::class, 'showByRole'])->name('dashboard.role');

    // Profile Management
    Route::get('/profile', [PenggunaController::class, 'profile'])->name('profile');
    Route::post('/profile', [PenggunaController::class, 'updateProfile'])->name('profile.update');


    // Admin Routes
    Route::middleware(['peran:admin'])->group(function () {
        Route::resource('pengguna', PenggunaController::class)->names('users')->except(['show']);
        Route::resource('tipe_fasilitas', FasilitasController::class)->names('tipe_fasilitas')->except(['show']);
        Route::resource('fasilitas', FasRuangController::class)->names('fasilitas')->except(['show']);
        Route::resource('ruang', RuangController::class)->except(['show']);
        Route::resource('gedung', GedungController::class)->except(['show']);
        Route::resource('periode', PeriodeController::class);
        Route::get('/fasilitas/{id}/qr', [FasRuangController::class, 'generateQR'])
            ->name('fasilitas.qr');
        Route::get('/fasilitas/{id}/history', [FasRuangController::class, 'history'])->name('fasilitas.history');
    });

    // Laporan Routes - All Authenticated Users
    Route::get('/laporan/riwayat', [LaporanKerusakanController::class, 'riwayat'])->name('laporan.riwayat');
    Route::resource('laporan', LaporanKerusakanController::class);
    Route::get('/laporan/detail/{laporan}', [LaporanKerusakanController::class, 'getDetail'])->name('laporan.detail');
    Route::get('/laporan/fasilitas-by-ruang/{ruang_id}', [LaporanKerusakanController::class, 'getFasilitasByRuang']);
    Route::get('/laporan/kode-by-ruang-fasilitas/{ruang_id}/{fasilitas_id}', [LaporanKerusakanController::class, 'getKodeByRuangFasilitas']);
    Route::get('/laporan/quick/{code}', [LaporanKerusakanController::class, 'quickReport'])->name('laporan.quick');


    // Sarpras Routes
    Route::middleware(['peran:sarpras'])->group(function () {
        Route::post('/laporan/{laporan}/verifikasi', [LaporanKerusakanController::class, 'verifikasi'])->name('laporan.verifikasi');
        Route::post('/laporan/batch-update-status', [LaporanKerusakanController::class, 'batchUpdateStatus'])->name('laporan.batchUpdateStatus');
        Route::get('/laporan/export', [LaporanKerusakanController::class, 'export'])->name('laporan.export');
        Route::put('/laporan/{laporan}/update-sarpras', [LaporanKerusakanController::class, 'update'])->name('laporan.updateSarpras');

        Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/create/{id_laporan}', [TugasController::class, 'create'])->name('tugas.create');
        Route::post('/tugas', [TugasController::class, 'store'])->name('tugas.store');
        Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('tugas.show');
        Route::get('/tugas/{id}/edit', [TugasController::class, 'edit'])->name('tugas.edit');
        Route::put('/tugas/{id}', [TugasController::class, 'update'])->name('tugas.update');
        Route::delete('/tugas/{id}', [TugasController::class, 'destroy'])->name('tugas.destroy');
    });

    // Shared Sarpras & Teknisi Routes
    Route::middleware(['peran:sarpras,teknisi'])->group(function () {
        Route::post('/laporan/{laporan}/status', [LaporanKerusakanController::class, 'updateStatus'])
            ->name('laporan.updateStatus');
    });

    // Teknisi Routes
    Route::middleware(['peran:teknisi'])->group(function () {
        Route::get('/teknisi/tugas', [TeknisiController::class, 'index'])->name('teknisi.index');
        Route::put('/teknisi/tugas/{id}', [TeknisiController::class, 'updateTugas'])->name('teknisi.updateTugas');
        Route::get('/teknisi/riwayat', [TeknisiController::class, 'riwayatPerbaikan'])->name('teknisi.riwayat');
    });

    // Notifications Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});
