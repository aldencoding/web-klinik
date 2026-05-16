<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KunjunganController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;
use App\Models\Kunjungan;

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

// Route Authentication
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.process');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('index');

    // Hak Akses Admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('dokter/jadwal', [DokterController::class, 'getJadwal'])->name('dokter.jadwal');
        Route::resource('dokter', DokterController::class);
        Route::resource('pasien', PasienController::class);

        // Route::get('daftar-antrian/nik/{nik}', [AntrianController::class, 'getNikPasien'])->name('daftar-antrian.getNikPasien');
        // Route::resource('daftar-antrian', AntrianController::class);
    });

    // Hak Akses Dokter
    Route::middleware(['role:admin'])->prefix('dokter')->group(function () {
        Route::get('daftar-antrian', [DokterController::class, 'getKunjungan'])->name('daftar-kunjungan');

        // Grouping Rekam Medis
        Route::prefix('rekam-medis/')->group(function () {
            Route::get('/', [DokterController::class, 'getRekamMedisPasien'])->name('rekam.medis.index');
            Route::post('/', [DokterController::class, 'postRekamMedisPasien'])->name('rekam.medis.store');
        });
    });

    // Grouping Kunjungan (antrian)
    Route::prefix('kunjungan/')->group(function () {
        Route::get('/nik', [KunjunganController::class, 'getNikPasien'])->name('kunjungan.nik');
        Route::get('/', [KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/create', [KunjunganController::class, 'create'])->name('kunjungan.create');
        Route::post('/', [KunjunganController::class, 'store'])->name('kunjungan.store');
    });
});
