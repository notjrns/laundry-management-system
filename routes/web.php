<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\RakKolomController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transaksi + cetak/kirim nota
    Route::get('transaksi/{transaksi}/nota', [TransaksiController::class, 'nota'])->name('transaksi.nota');
    Route::resource('transaksi', TransaksiController::class);

    // Rak (kelola rak + kolom)
    Route::resource('rak', RakController::class);
    Route::get('kolom/{rakKolom}/edit', [RakKolomController::class, 'edit'])->name('kolom.edit');
    Route::put('kolom/{rakKolom}', [RakKolomController::class, 'update'])->name('kolom.update');
    Route::delete('kolom/{rakKolom}', [RakKolomController::class, 'destroy'])->name('kolom.destroy');

    // Data karyawan
    Route::resource('karyawan', KaryawanController::class);

    // Laporan + export PDF
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
});
