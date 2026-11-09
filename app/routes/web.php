<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('supplier', SupplierController::class)->except(['show']);
    Route::resource('barang', BarangController::class)->except(['show']);

    Route::get('/transaksi/masuk', [TransaksiController::class, 'formMasuk'])->name('transaksi.masuk');
    Route::post('/transaksi/masuk', [TransaksiController::class, 'simpanMasuk'])->name('transaksi.masuk.store');
    Route::get('/transaksi/keluar', [TransaksiController::class, 'formKeluar'])->name('transaksi.keluar');
    Route::post('/transaksi/keluar', [TransaksiController::class, 'simpanKeluar'])->name('transaksi.keluar.store');
    Route::get('/riwayat/export', [RiwayatController::class, 'export'])->name('riwayat.export');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});
