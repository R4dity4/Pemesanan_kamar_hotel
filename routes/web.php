<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\RestoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KamarAdminController;
use App\Http\Controllers\Admin\TransaksiAdminController;
use App\Http\Controllers\Admin\PesanAdminController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Api\AvailabilityController;

// ==========================================
// ROUTES UNTUK PENGUNJUNG (PUBLIC)
// ==========================================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/kamar', [KamarController::class, 'index'])->name('kamar');
Route::get('/kamar/{no_kamar}', [KamarController::class, 'show'])->name('kamar.show');
Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas');
Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'send'])->name('kontak.send');
Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi');
Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
Route::get('/reservasi/cek', [ReservasiController::class, 'cekStatus'])->name('reservasi.cek');
Route::post('/reservasi/upload-bukti', [ReservasiController::class, 'uploadBukti'])->name('reservasi.upload');
Route::get('/reservasi/invoice/{no_transaksi}', [ReservasiController::class, 'invoice'])->name('reservasi.invoice');
Route::get('/resto', [RestoController::class, 'index'])->name('resto');

// ==========================================
// API ROUTES (Availability Calendar)
// ==========================================
Route::prefix('api')->group(function () {
    Route::post('/availability/check', [AvailabilityController::class, 'checkAvailability'])->name('api.availability.check');
    Route::get('/availability/calendar', [AvailabilityController::class, 'getCalendarData'])->name('api.availability.calendar');
    Route::post('/availability/lock-check', [AvailabilityController::class, 'lockCheck'])->name('api.availability.lock');
});

Route::get('pengunjung/medsos/instagaram', function () {
    return view('pengunjung.medsos.instagaram');
});
Route::get('pengunjung/medsos/fesnuk', function () {
    return view('pengunjung.medsos.fesnuk');
});
Route::get('pengunjung/medsos/twister', function () {
    return view('pengunjung.medsos.twister');
});
Route::get('pengunjung/medsos/privacypolicy', function () {
    return view('pengunjung.medsos.privacypolicy');
});

// ==========================================
// ROUTES UNTUK KARYAWAN (AUTH)
// ==========================================
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['karyawan'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Kelola Kamar
    Route::get('/kamar', [KamarAdminController::class, 'index'])->name('admin.kamar.index');
    Route::get('/kamar/create', [KamarAdminController::class, 'create'])->name('admin.kamar.create');
    Route::post('/kamar', [KamarAdminController::class, 'store'])->name('admin.kamar.store');
    Route::get('/kamar/{no_kamar}/edit', [KamarAdminController::class, 'edit'])->name('admin.kamar.edit');
    Route::put('/kamar/{no_kamar}', [KamarAdminController::class, 'update'])->name('admin.kamar.update');
    Route::delete('/kamar/{no_kamar}', [KamarAdminController::class, 'destroy'])->name('admin.kamar.destroy');

    // Kelola Transaksi
    Route::get('/transaksi', [TransaksiAdminController::class, 'index'])->name('admin.transaksi.index');
    Route::get('/transaksi/export', [TransaksiAdminController::class, 'export'])->name('admin.transaksi.export');
    Route::get('/transaksi/{no_transaksi}', [TransaksiAdminController::class, 'show'])->name('admin.transaksi.show');
    Route::post('/transaksi/{no_transaksi}/konfirmasi', [TransaksiAdminController::class, 'konfirmasiPesanan'])->name('admin.transaksi.konfirmasi');
    Route::post('/transaksi/{no_transaksi}/bayar', [TransaksiAdminController::class, 'konfirmasiPembayaran'])->name('admin.transaksi.bayar');
    Route::post('/transaksi/{no_transaksi}/selesai', [TransaksiAdminController::class, 'selesai'])->name('admin.transaksi.selesai');
    Route::post('/transaksi/{no_transaksi}/batal', [TransaksiAdminController::class, 'batal'])->name('admin.transaksi.batal');

    // Kelola Pesan
    Route::get('/pesan', [PesanAdminController::class, 'index'])->name('admin.pesan.index');
    Route::get('/pesan/{id}', [PesanAdminController::class, 'show'])->name('admin.pesan.show');
    Route::delete('/pesan/{id}', [PesanAdminController::class, 'destroy'])->name('admin.pesan.destroy');
    Route::post('/pesan/{id}/read', [PesanAdminController::class, 'markAsRead'])->name('admin.pesan.read');
    Route::post('/pesan/mark-all-read', [PesanAdminController::class, 'markAllAsRead'])->name('admin.pesan.markAllRead');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/download', [LaporanController::class, 'download'])->name('admin.laporan.download');
});
