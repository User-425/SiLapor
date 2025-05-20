<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\FasilitasController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'showLoginForm']);
Route::post('/', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/{role}', function ($role) {
        if (Auth::user()->peran !== $role && $role !== 'default') {
            abort(403, 'Unauthorized action.');
        }

        if (view()->exists("pages.dashboard.{$role}")) {
            return view("pages.dashboard.{$role}");
        }

        return view('dashboard.default');
    })->name('dashboard.role');

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('users.index');
    Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('users.create');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('users.store');
    Route::get('/pengguna/{pengguna}/edit', [PenggunaController::class, 'edit'])->name('users.edit');
    Route::put('/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('users.update');
    Route::delete('/pengguna/{pengguna}', [PenggunaController::class, 'destroy'])->name('users.destroy');

});

// Fasilitas
Route::prefix('fasilitas')->group(function () {
    Route::get('/', [FasilitasController::class, 'index'])->name('fasilitas.index');
    Route::get('/create', [FasilitasController::class, 'create'])->name('fasilitas.create');
    Route::post('/', [FasilitasController::class, 'store'])->name('fasilitas.store');
    Route::get('/{fasilitas}/edit', [FasilitasController::class, 'edit'])->name('fasilitas.edit');
    Route::put('/{fasilitas}', [FasilitasController::class, 'update'])->name('fasilitas.update');
    Route::delete('/{fasilitas}', [FasilitasController::class, 'destroy'])->name('fasilitas.destroy');
});


// Ruang
Route::prefix('ruang')->group(function () {
    Route::get('/', [RuangController::class, 'index'])->name('ruang.index');
    Route::get('/create', [RuangController::class, 'create'])->name('ruang.create');
    Route::post('/', [RuangController::class, 'store'])->name('ruang.store');
    Route::get('/{ruang}/edit', [RuangController::class, 'edit'])->name('ruang.edit');
    Route::put('/{ruang}', [RuangController::class, 'update'])->name('ruang.update');
    Route::delete('/{ruang}', [RuangController::class, 'destroy'])->name('ruang.destroy');
});



// Gedung
use App\Http\Controllers\GedungController;

Route::prefix('gedung')->group(function () {
    Route::get('/', [GedungController::class, 'index'])->name('gedung.index');
    Route::get('/create', [GedungController::class, 'create'])->name('gedung.create');
    Route::post('/', [GedungController::class, 'store'])->name('gedung.store');
    Route::get('/{gedung}/edit', [GedungController::class, 'edit'])->name('gedung.edit');
    Route::put('/{gedung}', [GedungController::class, 'update'])->name('gedung.update');
    Route::delete('/{gedung}', [GedungController::class, 'destroy'])->name('gedung.destroy');
});



// Periode
Route::resource('periode', PeriodeController::class);

//
