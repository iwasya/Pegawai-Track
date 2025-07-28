<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AbsensiPegawaiController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\SlipGajiController;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 🔐 USER
Route::resource('users', UserController::class)->except(['create', 'edit']);
Route::get('/export-users', fn() => Excel::download(new UsersExport, 'users.xlsx'))->name('users.export');

// 🔐 JABATAN
Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
Route::post('/jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
Route::put('/jabatan/{id}', [JabatanController::class, 'update'])->name('jabatan.update');
Route::delete('/jabatan/{id}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');
Route::get('/jabatan/export', [JabatanController::class, 'export'])->name('jabatan.export');

// 🔐 PEGAWAI
Route::resource('pegawai', PegawaiController::class);
Route::get('/pegawai/export', [PegawaiController::class, 'export'])->name('pegawai.export');
Route::post('/pegawai/restore/{id}', [PegawaiController::class, 'restore'])->name('pegawai.restore');
Route::delete('/pegawai/force-delete/{id}', [PegawaiController::class, 'forceDelete'])->name('pegawai.forceDelete');

// 🔐 CUTI
Route::get('/cuti', [CutiController::class, 'index']);
Route::post('/cuti/store', [CutiController::class, 'store']);
Route::post('/cuti/update/{id}', [CutiController::class, 'update']);
Route::post('/cuti/delete/{id}', [CutiController::class, 'destroy']);
Route::get('/cuti/export', [CutiController::class, 'export']);
Route::get('/cuti/ajukan', [CutiController::class, 'ajukan'])->middleware('auth');
Route::post('/cuti/persetujuan/{id}', [CutiController::class, 'persetujuan'])->name('cuti.persetujuan');

// 🔐 PROFIL & JADWAL SAYA
Route::get('/profil', [ProfilController::class, 'index'])->middleware('auth');
Route::get('/jadwal-pegawai', [ProfilController::class, 'jadwalSaya'])->name('jadwal.pegawai');

// 🔐 JADWAL ADMIN
Route::middleware(['auth'])->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::get('/jadwal/export', [JadwalController::class, 'export'])->name('jadwal.export');
    Route::get('/jadwal-generate', [JadwalController::class, 'generateMingguan'])->name('jadwal.generate');
    Route::delete('/jadwal-hapus-semua', [JadwalController::class, 'hapusSemua'])->name('jadwal.hapus-semua');
});

// 🔐 ABSENSI
Route::middleware(['auth'])->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::put('/absensi/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
});

// 🔐 ABSENSI PEGAWAI
Route::middleware(['auth'])->prefix('absensi/pegawai')->name('pegawai.absensi.')->group(function () {
    Route::get('/', [AbsensiPegawaiController::class, 'index'])->name('index');
    Route::post('/store', [AbsensiPegawaiController::class, 'store'])->name('store');
});

// 🔐 SLIP GAJI PRIBADI
Route::get('/gaji/saya', [SlipGajiController::class, 'index'])
    ->middleware('auth')
    ->name('gaji.saya');
Route::get('/gaji/saya/{id}', [SlipGajiController::class, 'show'])
    ->middleware('auth')
    ->name('gaji.saya.show');
Route::get('/gaji/saya/download/{id}', [App\Http\Controllers\SlipGajiController::class, 'downloadPdf'])
    ->name('gaji.saya.download');


// 🔐 GAJI (admin/HRD)
Route::resource('/gaji', GajiController::class)
    ->except(['show']) // <== ini penting untuk menghindari error show
    ->middleware('auth');


use App\Http\Controllers\AdminDashboardController;

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
