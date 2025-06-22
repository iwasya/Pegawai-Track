<?php

use Illuminate\Support\Facades\Route;

use app\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::resource('users', App\Http\Controllers\UserController::class);


Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');


use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/export-users', function () {
    return Excel::download(new UsersExport, 'users.xlsx');
})->name('users.export');

use App\Http\Controllers\JabatanController;

Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
Route::post('/jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
Route::put('/jabatan/{id}', [JabatanController::class, 'update'])->name('jabatan.update');
Route::delete('/jabatan/{id}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');
Route::get('/jabatan/export', [JabatanController::class, 'export'])->name('jabatan.export');

use App\Http\Controllers\PegawaiController;
Route::get('/pegawai/export', [PegawaiController::class, 'export'])->name('pegawai.export');

// routes/web.php
Route::resource('pegawai', \App\Http\Controllers\PegawaiController::class);

// cuti routes
use App\Http\Controllers\CutiController;

Route::get('/cuti', [CutiController::class, 'index']);
Route::post('/cuti/store', [CutiController::class, 'store']);
Route::post('/cuti/update/{id}', [CutiController::class, 'update']);
Route::post('/cuti/delete/{id}', [CutiController::class, 'destroy']);
Route::get('/cuti/export', [CutiController::class, 'export']);


Route::get('/cuti/ajukan', [CutiController::class, 'ajukan'])->middleware('auth');

Route::post('/cuti/persetujuan/{id}', [CutiController::class, 'persetujuan'])->name('cuti.persetujuan');
Route::post('/cuti/persetujuan/{id}', [CutiController::class, 'persetujuan'])->name('cuti.persetujuan');
Route::post('/pegawai/restore/{id}', [PegawaiController::class, 'restore'])->name('pegawai.restore');
Route::delete('/pegawai/force-delete/{id}', [PegawaiController::class, 'forceDelete'])->name('pegawai.forceDelete');



use App\Http\Controllers\ProfilController;

Route::get('/profil', [ProfilController::class, 'index'])->middleware('auth');

Route::get('/jadwal-pegawai', [\App\Http\Controllers\ProfilController::class, 'jadwalSaya'])->name('jadwal.pegawai');


use App\Http\Controllers\JadwalController;

Route::middleware(['auth'])->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::get('/jadwal/export', [JadwalController::class, 'export'])->name('jadwal.export');
});

Route::get('/jadwal-generate', [JadwalController::class, 'generateMingguan'])->name('jadwal.generate');
Route::delete('/jadwal-hapus-semua', [JadwalController::class, 'hapusSemua'])->name('jadwal.hapus-semua');

