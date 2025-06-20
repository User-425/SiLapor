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
use App\Http\Controllers\UmpanBalikController;
use App\Http\Controllers\EksporSarprasController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{role}', [DashboardController::class, 'showByRole'])->name('dashboard.role');

    // Profile Management
    Route::get('/profile', [PenggunaController::class, 'profile'])->name('profile');
    Route::post('/profile', [PenggunaController::class, 'updateProfile'])->name('profile.update');

    // Laporan Routes - All Authenticated Users
    Route::get('/laporan/riwayat', [LaporanKerusakanController::class, 'riwayat'])->name('laporan.riwayat');
    Route::resource('laporan', LaporanKerusakanController::class);
    Route::get('/laporan/detail/{laporan}', [LaporanKerusakanController::class, 'getDetail'])->name('laporan.detail');
    Route::get('/laporan/fasilitas-by-ruang/{ruang_id}', [LaporanKerusakanController::class, 'getFasilitasByRuang']);
    Route::get('/laporan/kode-by-ruang-fasilitas/{ruang_id}/{fasilitas_id}', [LaporanKerusakanController::class, 'getKodeByRuangFasilitas']);
    Route::get('/laporan/quick/{code}', [LaporanKerusakanController::class, 'quickReport'])->name('laporan.quick');
    Route::get('/umpan-balik/{id_laporan}/create', [UmpanBalikController::class, 'create'])->name('umpan_balik.create');
    Route::post('/umpan-balik', [UmpanBalikController::class, 'store'])->name('umpan_balik.store');

    // Admin Routes
    Route::middleware(['peran:admin'])->group(function () {
        Route::resource('pengguna', PenggunaController::class)->names('users')->except(['show']);
        Route::patch('/users/{id}/restore', [PenggunaController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force-delete', [PenggunaController::class, 'forceDelete'])->name('users.force-delete');
        Route::resource('tipe_fasilitas', FasilitasController::class)->names('tipe_fasilitas')->except(['show']);
        Route::resource('fasilitas', FasRuangController::class)->names('fasilitas')->except(['show']);
        Route::resource('ruang', RuangController::class)->except(['show']);
        Route::resource('gedung', GedungController::class)->names(names: 'gedung')->except(['show']);
        Route::resource('periode', PeriodeController::class);
        Route::get('/fasilitas/{id}/qr', [FasRuangController::class, 'generateQR'])->name('fasilitas.qr');
        Route::get('/fasilitas/{id}/show', [FasRuangController::class, 'show'])->name('fasilitas.show');
        Route::get('/fasilitas/{id}/history', [FasRuangController::class, 'history'])->name('fasilitas.history');
    });

    // Sarpras Routes
    Route::middleware(['peran:sarpras'])->group(function () {
        Route::post('/laporan/{laporan}/verifikasi', [LaporanKerusakanController::class, 'verifikasi'])->name('laporan.verifikasi');
        Route::post('/laporan/batch-update-status', [LaporanKerusakanController::class, 'batchUpdateStatus'])->name('laporan.batchUpdateStatus');
        Route::put('/laporan/{laporan}/update-sarpras', [LaporanKerusakanController::class, 'update'])->name('laporan.updateSarpras');
        Route::put('/sarpras/laporan/{laporan}', [SarprasLaporanController::class, 'update'])->name('sarpras.laporan.update');
        // Export
        Route::get('/sarpras/export-laporan', [EksporSarprasController::class, 'form'])->name('laporan.export');
        Route::post('/sarpras/download-export', [EksporSarprasController::class, 'export'])->name('laporan.download-export');

        // Tugas management
        Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/create/{laporan}', [TugasController::class, 'create'])->name('tugas.create');
        Route::post('/tugas', [TugasController::class, 'store'])->name('tugas.store');
        Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('tugas.show');
        Route::get('/tugas/{id}/edit', [TugasController::class, 'edit'])->name('tugas.edit');
        Route::put('/tugas/{id}', [TugasController::class, 'update'])->name('tugas.update');
        Route::delete('/tugas/{id}', [TugasController::class, 'destroy'])->name('tugas.destroy');
        Route::get('/tugas/laporan-details/{id}', [TugasController::class, 'getLaporanDetails']);

        // Umpan balik
        Route::get('/umpan-balik', [UmpanBalikController::class, 'index'])->name('umpan_balik.index');

        // Batch management
        Route::get('/batches', [BatchController::class, 'index'])->name('batches.index');
        Route::get('/batches/create', [BatchController::class, 'create'])->name('batches.create');
        Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
        Route::get('/batches/{batch}', [BatchController::class, 'show'])->name('batches.show');
        Route::post('/batches/{batch}/activate', [BatchController::class, 'activate'])->name('batches.activate');
        Route::post('/batches/{batch}/complete', [BatchController::class, 'complete'])->name('batches.complete');
        Route::get('/batches/{batch}/add-reports', [BatchController::class, 'showAddReports'])->name('batches.add-reports');
        Route::post('/batches/{batch}/add-reports', [BatchController::class, 'addReports'])->name('batches.add-reports');
        Route::post('/batches/{batch}/remove-report/{laporan}', [BatchController::class, 'removeReport'])->name('batches.remove-report');
        Route::get('/batches/{batch}/calculations', [BatchController::class, 'showCalculations'])->name('batches.calculations');
        Route::get('/batches/{batch}/ranking', [BatchController::class, 'showRankingPage'])->name('batches.ranking');
        Route::post('/batches/{batch}/save-rankings', [BatchController::class, 'saveRankings'])->name('batches.save-rankings');
        Route::get('/batches/{batch}/gdss', [BatchController::class, 'showGDSSRankingPage'])->name('batches.gdss');
        Route::get('/batches/{batch}/gdss-calculations', [BatchController::class, 'showGDSSCalculations'])->name('batches.gdss-calculations');
        Route::post('/batches/{batch}/gdss-rankings', [BatchController::class, 'saveGDSSRankings'])->name('batches.save-gdss-rankings');

        // Fasilitas history
        Route::get('/fasilitas/{id}/history', [FasRuangController::class, 'history'])->name('fasilitas.history');
    });

    // Shared Sarpras & Teknisi Routes
    Route::middleware(['peran:sarpras,teknisi'])->group(function () {
        Route::post('/laporan/{laporan}/status', [LaporanKerusakanController::class, 'updateStatus'])->name('laporan.updateStatus');
    });

    // Teknisi Routes
    Route::middleware(['peran:teknisi'])->group(function () {
        Route::get('/teknisi/tugas', [TeknisiController::class, 'index'])->name('teknisi.index');
        Route::put('/teknisi/tugas/{id}', [TeknisiController::class, 'updateTugas'])->name('teknisi.updateTugas');
        Route::get('/teknisi/riwayat', [TeknisiController::class, 'riwayatPerbaikan'])->name('teknisi.riwayat');
        Route::get('/fasilitas/{id}/maintenance', [FasRuangController::class, 'maintenance'])->name('fasilitas.maintenance');
    });

    // Search Routes
    Route::get('/search', [SearchController::class, 'index'])->name('global.search');

    // Notifications Routes (semua user yang login)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});
